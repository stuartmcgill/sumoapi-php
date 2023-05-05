<?php

declare(strict_types=1);

namespace Unit\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
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
        $mockClient = $this->mockFetchOne(
            id: 1,
            json: file_get_contents(__DIR__ . '/../../_data/rikishi_1.json')
        );

        $service = new RikishiService($mockClient);
        $rikishi = $service->fetch(1);

        $this->assertSame(1, $rikishi->id);
        $this->assertSame('Takakeisho', $rikishi->shikonaEn);
    }

    #[Test]
    public function fetchAll(): void
    {
        $json = file_get_contents(__DIR__ . '/../../_data/rikishis.json');

        $mockResponse = Mockery::mock(Response::class);
        $mockResponse
            ->expects('getBody->__toString')
            ->once()
            ->andReturn($json);

        $mockClient = Mockery::mock(Client::class);
        $mockClient
            ->expects('get')
            ->once()
            ->with('https://sumo-api.com/api/rikishis')
            ->andReturn($mockResponse);

        $service = new RikishiService($mockClient);
        $rikishis = $service->fetchAll();

        $this->assertCount(617, $rikishis);
    }

    #[Test]
    public function fetchSome(): void
    {
        $mockClient = $this->mockFetchSome([
            1 => file_get_contents(__DIR__ . '/../../_data/rikishi_1.json'),
            2 => file_get_contents(__DIR__ . '/../../_data/rikishi_2.json'),
        ]);

        $service = new RikishiService($mockClient);
        $rikishis = $service->fetchSome([1, 2]);

        $this->assertCount(2, $rikishis);
    }

    #[Test]
    public function fetchSomeWithTooManyWrestlers(): void
    {
        $service = new RikishiService(Mockery::mock(Client::class));

        $this->expectException(InvalidArgumentException::class);
        $service->fetchSome(array_fill(0, 51, 0));
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

    private function mockFetchOne(int $id, string $json): Client
    {
        $mockResponse = Mockery::mock(Response::class);
        $mockResponse
            ->expects('getBody->__toString')
            ->once()
            ->andReturn($json);

        $mockClient = Mockery::mock(Client::class);
        $mockClient
            ->expects('get')
            ->once()
            ->with("https://sumo-api.com/api/rikishi/$id")
            ->andReturn($mockResponse);

        return $mockClient;
    }

    /** @param array<int, string> $jsonData */
    private function mockFetchSome(array $jsonData): Client
    {
        $mockClient = Mockery::mock(Client::class);

        foreach ($jsonData as $id => $json) {
            $response = Mockery::mock(Response::class);
            $response
                ->expects('getBody->__toString')
                ->once()
                ->andReturn($json);

            $promise = Mockery::mock(PromiseInterface::class);
            $promise->expects('wait')->once()->andReturn($response);

            $mockClient
                ->expects('getAsync')
                ->once()
                ->with("https://sumo-api.com/api/rikishi/$id")
                ->andReturn($promise);
        }

        return $mockClient;
    }
}
