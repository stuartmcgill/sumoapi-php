<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Service;

use GuzzleHttp\Client;
use stdClass;

class BashoService
{
    private const URL = 'https://sumo-api.com/api/';

    public function __construct(private readonly Client $httpClient)
    {
    }

    /** @return list<int> */
    public function fetchRikishiIdsByBasho(int $year, int $month, string $division): array
    {
        $bashoDate = sprintf("%d%02d", $year, $month);

        $response = $this->httpClient->get(self::URL . "basho/$bashoDate/banzuke/$division");
        $data = json_decode((string)$response->getBody());

        $east = array_map(
            callback: static fn (stdClass $rikishi) => $rikishi->rikishiID,
            array: $data->east
        );

        $west = array_map(
            callback: static fn (stdClass $rikishi) => $rikishi->rikishiID,
            array: $data->west
        );

        return array_merge($east, $west);
    }

    /** @codeCoverageIgnore */
    public static function factory(): self
    {
        return new self(new Client());
    }
}
