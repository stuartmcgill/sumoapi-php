<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Service;

use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use stdClass;
use StuartMcGill\SumoApiPhp\Factory\MatchupFactory;
use StuartMcGill\SumoApiPhp\Factory\RikishiFactory;
use StuartMcGill\SumoApiPhp\Factory\RikishiMatchFactory;
use StuartMcGill\SumoApiPhp\Model\MatchupSummary;
use StuartMcGill\SumoApiPhp\Model\Rank;
use StuartMcGill\SumoApiPhp\Model\Rikishi;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;
use StuartMcGill\SumoApiPhp\Model\SubDivision;

class RikishiService
{
    private const URL = 'https://sumo-api.com/api/';
    private const MAX_PARALLEL_CALLS = 50;

    /** @param array<string, mixed> $config */
    public function __construct(private readonly Client $httpClient, private readonly array $config)
    {
    }

    public function fetch(int $rikishiId): ?Rikishi
    {
        $response = $this->httpClient->get(self::URL . "rikishi/$rikishiId");
        $json = (string)$response->getBody();

        return $json === ''
            ? null
            : (new RikishiFactory())->build(json_decode($json));
    }

    /** @return list<Rikishi> */
    public function fetchAll(): array
    {
        $response = $this->httpClient->get(self::URL . 'rikishis');
        $data = json_decode((string)$response->getBody());

        $factory = new RikishiFactory();

        return array_values(array_map(
            callback: static fn (stdClass $rikishiData) => $factory->build($rikishiData),
            array:$data->records
        ));
    }

    /** @return array<string, list<Rikishi>> */
    public function fetchAllByDivision(bool $excludeBanzukeGai = false): array
    {
        $grouped = array_reduce(
            array: $this->fetchAll(),
            callback: static function (array $grouped, Rikishi $rikishi) use ($excludeBanzukeGai) {
                $rank = new Rank($rikishi->currentRank);
                $division = $rank->division();

                if ($excludeBanzukeGai && $division === 'Banzuke-gai') {
                    return $grouped;
                }

                if (!array_key_exists(key: $division, array: $grouped)) {
                    $grouped[$division] = [];
                }

                $grouped[$division][] = $rikishi;

                return $grouped;
            },
            initial: [],
        );

        uksort(
            array: $grouped,
            callback: static function (string $a, string $b): int {
                if ($a === $b) {
                    return 0;
                }

                return (new SubDivision($a))->isGreaterThan((new SubDivision($b)))
                    ? -1
                    : 1;
            },
        );

        return $grouped;
    }

    /**
     * Fetches the details of the requested wrestlers (max. 50) in parallel
     *
     * @param list<int> $ids
     * @return list<Rikishi>
     */
    public function fetchSome(array $ids): array
    {
        $this->assertMaxParallelCalls(count($ids));

        $baseUrl = self::URL . 'rikishi/';

        $promises = array_map(
            callback: fn (int $id) => $this->httpClient->getAsync($baseUrl . $id),
            array: $ids,
        );
        $responses = Utils::settle(Utils::unwrap($promises))->wait();

        $factory = new RikishiFactory();

        return array_values(array_map(
            static fn (array $response) =>
                $factory->build(json_decode((string)$response['value']->getBody())),
            $responses,
        ));
    }

    /** @return list<RikishiMatch> */
    public function fetchMatches(int $rikishiId): array
    {
        $response = $this->httpClient->get(self::URL . "rikishi/$rikishiId/matches");
        $data = json_decode((string)$response->getBody());

        $factory = new RikishiMatchFactory();

        return array_values(array_map(
            callback: static fn (stdClass $matchData) => $factory->build($matchData),
            array:$data->records
        ));
    }

    /** @return list<Rikishi> */
    public function fetchDivision(string $division): array
    {
        if (!in_array(needle: $division, haystack: $this->config['divisions'])) {
            throw new InvalidArgumentException(
                'Please specify one of the following divisions: ' .
                    implode(separator: ',', array: $this->config['divisions'])
            );
        }

        $response = $this->httpClient->get(self::URL . 'rikishis');
        $data = json_decode((string)$response->getBody());

        $divisionData = array_values(array_filter(
            array: $data->records,
            callback: static function (stdClass $rikishiData) use ($division) {
                if (empty($rikishiData->currentRank)) {
                    return false;
                }
                $rank = new Rank($rikishiData->currentRank);

                return $rank->division() === $division;
            }
        ));

        $factory = new RikishiFactory();

        return array_values(array_map(
            callback: static fn (stdClass $rikishiData) => $factory->build($rikishiData),
            array:$divisionData
        ));
    }

    /** @param list<int> $opponentIds */
    public function fetchMatchups(int $rikishiId, array $opponentIds): MatchupSummary
    {
        $this->assertMaxParallelCalls(count($opponentIds));

        // Prepare and populate an array of head-to-head records keyed by the opponent ID
        $opponentRecords = array_flip($opponentIds);

        $pool = new Pool(
            $this->httpClient,
            $this->generateMatchupRequests($rikishiId, $opponentIds),
            [
                'concurrency' => 10,
                'fulfilled' => function (
                    Response $response,
                    $index,
                ) use (
                    &$opponentRecords,
                    $opponentIds
                ) {
                    $opponentId = $opponentIds[$index];
                    $record = json_decode((string)$response->getBody());

                    $opponentRecords[$opponentId] = $record;
                },
            ],
        );
        $promise = $pool->promise();
        $promise->wait();

        $factory = new MatchupFactory();
        $matchups = [];
        foreach ($opponentRecords as $opponentId => $record) {
            $matchups[] = $factory->build($rikishiId, $opponentId, $record);
        }

        return new MatchupSummary($rikishiId, $matchups);
    }

    /** @param list<int> $opponentIds */
    private function generateMatchupRequests(int $rikishiId, array $opponentIds): Generator
    {
        foreach ($opponentIds as $opponentId) {
            yield new Request(
                'GET',
                self::URL . "rikishi/$rikishiId/matches/" . $opponentId,
            );
        }
    }

    private function assertMaxParallelCalls(int $numCalls): void
    {
        if ($numCalls > self::MAX_PARALLEL_CALLS) {
            throw new InvalidArgumentException(
                'The maximum number of IDs that can be requested in one call is '
                . self::MAX_PARALLEL_CALLS
            );
        }
    }

    /** @codeCoverageIgnore */
    public static function factory(): self
    {
        $config = include __DIR__ . '/../../config/config.php';

        return new self(new Client(), $config);
    }
}
