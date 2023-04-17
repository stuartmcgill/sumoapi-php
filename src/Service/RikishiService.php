<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Service;

use GuzzleHttp\Client;
use stdClass;
use StuartMcGill\SumoApiPhp\Factory\RikishiFactory;
use StuartMcGill\SumoApiPhp\Factory\RikishiMatchFactory;
use StuartMcGill\SumoApiPhp\Model\Rikishi;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;

class RikishiService
{
    private const URL = 'https://sumo-api.com/api/rikishi/';

    public function __construct(private readonly Client $httpClient)
    {
    }

    public function fetch(int $rikishiId): Rikishi
    {
        $response = $this->httpClient->get(self::URL . $rikishiId);
        $json = (string)$response->getBody();

        return (new RikishiFactory())->build(json_decode($json));
    }

    /** @return list<RikishiMatch> */
    public function fetchMatches(int $rikishiId): array
    {
        $response = $this->httpClient->get(self::URL . $rikishiId . '/matches');
        $data = json_decode((string)$response->getBody());

        $factory = new RikishiMatchFactory();

        return array_values(array_map(
            callback: static fn (stdClass $matchData) => $factory->build($matchData),
            array:$data->records
        ));
    }
}
