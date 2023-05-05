<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use InvalidArgumentException;
use stdClass;
use StuartMcGill\SumoApiPhp\Factory\RikishiFactory;
use StuartMcGill\SumoApiPhp\Factory\RikishiMatchFactory;
use StuartMcGill\SumoApiPhp\Model\Rikishi;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;

class RikishiService
{
    private const URL = 'https://sumo-api.com/api/';
    private const MAX_PARALLEL_CALLS = 50;

    public function __construct(private readonly Client $httpClient)
    {
    }

    public function fetch(int $rikishiId): Rikishi
    {
        $response = $this->httpClient->get(self::URL . "rikishi/$rikishiId");
        $json = (string)$response->getBody();

        return (new RikishiFactory())->build(json_decode($json));
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

    /**
     * Fetches the details of the requested wrestlers (max. 50) in parallel
     *
     * @param list<int> $ids
     * @return list<Rikishi>
     */
    public function fetchSome(array $ids): array
    {
        if (count($ids) > self::MAX_PARALLEL_CALLS) {
            throw new InvalidArgumentException(
                'The maximum number of IDs that can be requested in one call is '
                . self::MAX_PARALLEL_CALLS
            );
        }

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

    public static function factory(): self
    {
        return new self(new Client());
    }
}
