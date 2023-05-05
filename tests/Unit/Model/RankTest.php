<?php

declare(strict_types=1);

namespace Unit\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Model\Rank;

class RankTest extends TestCase
{
    #[DataProvider('makuuchiProvider')]
    #[Test]
    public function makuuchi(string $makuuchiRank): void
    {
        $rank = new Rank($makuuchiRank);
        $this->assertSame('Makuuchi', $rank->division());
    }

    /** @return list<list<string>> */
    public static function makuuchiProvider(): array
    {
        return [
            ['Yokozuna 1 East'],
            ['Ozeki 2 West'],
            ['Sekiwake 1 East'],
            ['Komusubi 2 East'],
            ['Maegashira 14 West'],
        ];
    }

    #[DataProvider('lowerDivisionProvider')]
    #[Test]
    public function lowerDivision(string $apiRank, string $expectedDivision): void
    {
        $rank = new Rank($apiRank);
        $this->assertSame($expectedDivision, $rank->division());
    }

    /** @return array<string, array<string, string>> */
    public static function lowerDivisionProvider(): array
    {
        return [
            'Juryo' => [
                'apiRank' => 'Juryo 1 East',
                'division' => 'Juryo',
            ],
            'Makushita' => [
                'apiRank' => 'Makushita 60 West',
                'division' => 'Makushita',
            ],
            'Sandanme' => [
                'apiRank' => 'Sandanme 12 East',
                'division' => 'Sandanme',
            ],
            'Jonidan' => [
                'apiRank' => 'Jonidan 45 East',
                'division' => 'Jonidan',
            ],
            'Jonokuchi' => [
                'apiRank' => 'Jonokuchi 4 West',
                'division' => 'Jonokuchi',
            ],
        ];
    }

    public function unknownRank(): void
    {
        $rank = new Rank('Banzuke-gai');
        $this->assertNull($rank->division());
    }
}
