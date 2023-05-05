<?php

declare(strict_types=1);

namespace Unit\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Service\BashoService;

class BashoServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var Client|MockInterface */
    private $httpClient;

    public function setUp(): void
    {
        $this->httpClient = Mockery::mock(Client::class);
    }

    #[Test]
    public function fetchRikishiIdsByBasho(): void
    {
        $response = Mockery::mock(Response::class);
        $response
            ->expects('getBody->__toString')
            ->once()
            ->andReturn('{
                "east": [
                    {
                        "rikishiID": 1
                    },
                    {
                        "rikishiID": 2
                    }
                ],
                "west": [
                    {
                        "rikishiID": 3
                    },
                    {
                        "rikishiID": 4
                    }
                ]
            }');

        $this->httpClient->expects('get')->once()->andReturn($response);

        $bashoService = new BashoService($this->httpClient);
        $rikishiIds = $bashoService->fetchRikishiIdsByBasho(
            year: 2023,
            month: 3,
            division: 'TEST_DIVISION',
        );

        $this->assertEquals(
            expected: [1, 2, 3, 4],
            actual: $rikishiIds,
        );
    }
}
