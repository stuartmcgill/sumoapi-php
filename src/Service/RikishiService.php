<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Service;

use GuzzleHttp\Client;
use StuartMcGill\SumoApiPhp\Factory\RikishiFactory;
use StuartMcGill\SumoApiPhp\Model\Rikishi;

class RikishiService
{
    public function __construct(private readonly Client $httpClient)
    {
    }

    public function fetch(int $id): Rikishi
    {
        $response = $this->httpClient->get("https://sumo-api.com/api/rikishi/$id");
        $json = (string)$response->getBody();

        return (new RikishiFactory())->build(json_decode($json));
    }
}
