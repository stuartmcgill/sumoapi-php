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
use StuartMcGill\SumoApiPhp\Service\KimariteService;

class KimariteServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private Client|MockInterface $httpClient;

    public function setUp(): void
    {
        $this->httpClient = Mockery::mock(Client::class);
    }

    #[Test]
    public function fetchByType(): void
    {
        $response = Mockery::mock(Response::class);
        $response
            ->expects('getBody->__toString')
            ->once()
            ->andReturn('{
                "records": [
                    {
                        "id": "-5-13-513-8850",
                        "bashoId": "",
                        "division": "Jonidan",
                        "day": 5,
                        "matchNo": 14,
                        "eastId": 513,
                        "eastShikona": "Wakasa",
                        "eastRank": "Jonidan 73 East",
                        "westId": 8850,
                        "westShikona": "Masarufuji",
                        "westRank": "Jonidan 72 West",
                        "kimarite": "yorikiri",
                        "winnerId": 513,
                        "winnerEn": "Wakasa",
                        "winnerJp": "若狹"
                    },
                    {
                        "id": "-5-11-165-8884",
                        "bashoId": "",
                        "division": "Makushita",
                        "day": 5,
                        "matchNo": 12,
                        "eastId": 165,
                        "eastShikona": "Kamito",
                        "eastRank": "Makushita 36 East",
                        "westId": 8884,
                        "westShikona": "Aonishiki",
                        "westRank": "Makushita 40 East",
                        "kimarite": "yorikiri",
                        "winnerId": 8884,
                        "winnerEn": "Aonishiki",
                        "winnerJp": "安青錦"
                    }
                ]
            }');

        $this->httpClient->expects('get')->once()->andReturn($response);

        $kimariteService = new KimariteService($this->httpClient);
        $matches = $kimariteService->fetchByType(
            type: 'yorikiri',
        );

        $this->assertCount(2, $matches);
    }
}
