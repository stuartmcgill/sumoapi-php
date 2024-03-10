<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Service;

use GuzzleHttp\Client;
use stdClass;
use StuartMcGill\SumoApiPhp\Factory\RikishiFactory;
use StuartMcGill\SumoApiPhp\Model\Rikishi;

class BashoService
{
    private const URL = 'https://sumo-api.com/api/';

    public function __construct(private readonly Client $httpClient)
    {
    }

    /** @return list<int> */
    public function fetchRikishiIdsByBasho(int $year, int $month, string $division): array
    {
        $data = $this->fetchBanzuke($year, $month, $division);

        return array_map(
            callback: static fn (stdClass $rikishi) => $rikishi->rikishiID,
            array: array_merge($data->east, $data->west),
        );
    }

    public function getRikishiFromBanzuke(
        int $year,
        int $month,
        string $division,
        int $rikishiId,
    ): ?Rikishi {
        $data = $this->fetchBanzuke($year, $month, $division);

        $matches = array_values(array_filter(
            array: array_merge($data->east, $data->west),
            callback: static fn (stdClass $rikishi) => $rikishi->rikishiID === $rikishiId,
        ));

        if (count($matches) === 0) {
            return null;
        }

        $rikishiData = new stdClass();
        $rikishiData->id = $matches[0]->rikishiID;
        $rikishiData->shikonaEn = $matches[0]->shikonaEn;
        $rikishiData->currentRank = $matches[0]->rank;

        return (new RikishiFactory())->build($rikishiData);
    }

    /** @codeCoverageIgnore */
    public static function factory(): self
    {
        return new self(new Client());
    }

    private function fetchBanzuke(int $year, int $month, string $division): stdClass
    {
        $bashoDate = sprintf("%d%02d", $year, $month);

        $response = $this->httpClient->get(self::URL . "basho/$bashoDate/banzuke/$division");
        $data = json_decode((string)$response->getBody());

        return $data;
    }
}
