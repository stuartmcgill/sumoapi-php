<?php

declare(strict_types=1);

namespace Unit\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Service\RikishiService;

class RikishiServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function fetch(): void
    {
        $json = file_get_contents(__DIR__ . '/../../_data/rikishi.json');

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse
            ->expects('getBody->__toString')
            ->once()
            ->andReturn($json);

        $mockClient = Mockery::mock(Client::class);
        $mockClient
            ->expects('get')
            ->once()
            ->with('https://sumo-api.com/api/rikishi/1')
            ->andReturn($mockResponse);

        $service = new RikishiService($mockClient);
        $rikishi = $service->fetch(1);

        $this->assertSame(1, $rikishi->id);
        $this->assertSame('Takakeisho', $rikishi->name);
    }

    #[Test]
    public function fetchMatches(): void
    {
        $json = file_get_contents(__DIR__ . '/../../_data/rikishiMatches.json');

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse
            ->expects('getBody->__toString')
            ->once()
            ->andReturn($json);

        $mockClient = Mockery::mock(Client::class);
        $mockClient
            ->expects('get')
            ->once()
            ->with('https://sumo-api.com/api/rikishi/1/matches')
            ->andReturn($mockResponse);

        $service = new RikishiService($mockClient);
        $matches = $service->fetchMatches(1);

        $this->assertCount(622, $matches);
    }
}
