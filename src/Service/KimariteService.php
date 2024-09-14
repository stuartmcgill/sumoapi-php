<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Service;

use GuzzleHttp\Client;
use stdClass;
use StuartMcGill\SumoApiPhp\Factory\RikishiMatchFactory;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;

class KimariteService
{
    private const URL = 'https://sumo-api.com/api/';

    public function __construct(private readonly Client $httpClient)
    {
    }

    /** @return list<RikishiMatch> */
    public function fetchByType(
        string $type,
        ?string $sortOrder = 'desc',
        ?int $limit = 0,
        ?int $skip = 0,
    ): array {
        $response = $this->httpClient->get(self::URL . "kimarite/$type?limit=$limit&skip=$skip");
        $data = json_decode((string)$response->getBody());

        $factory = new RikishiMatchFactory();
        $records = $data->records;

        return array_map(
            callback: static fn (stdClass $matchData) => $factory->build($matchData),
            array: $records,
        );
    }

    /** @codeCoverageIgnore */
    public static function factory(): self
    {
        return new self(new Client());
    }
}
