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
                'expectedDivision' => 'Juryo',
            ],
            'Makushita' => [
                'apiRank' => 'Makushita 60 West',
                'expectedDivision' => 'Makushita',
            ],
            'Sandanme' => [
                'apiRank' => 'Sandanme 12 East',
                'expectedDivision' => 'Sandanme',
            ],
            'Jonidan' => [
                'apiRank' => 'Jonidan 45 East',
                'expectedDivision' => 'Jonidan',
            ],
            'Jonokuchi' => [
                'apiRank' => 'Jonokuchi 4 West',
                'expectedDivision' => 'Jonokuchi',
            ],
        ];
    }

    #[Test]
    public function unknownRank(): void
    {
        $rank = new Rank('Banzuke-gai');
        $this->assertSame(expected: 'Banzuke-gai', actual: $rank->division());
    }

    #[DataProvider('isGreaterThanProvider')]
    #[Test]
    public function isGreaterThan(string $apiRankA, string $apiRankB, bool $expected): void
    {
        $rankA = new Rank($apiRankA);
        $rankB = new Rank($apiRankB);

        $this->assertSame(expected: $expected, actual: $rankA->isGreaterThan($rankB));
    }

    /** @return array<string, array<string, mixed>> */
    public static function isGreaterThanProvider(): array
    {
        return [
            'Same' => [
                'apiRankA' => 'Maegashira 1 East',
                'apiRankB' => 'Maegashira 1 East',
                'expected' => false,
            ],
            'Same division and number, a is greater' => [
                'apiRankA' => 'Maegashira 1 East',
                'apiRankB' => 'Maegashira 1 West',
                'expected' => true,
            ],
            'Same division and number, a is lesser' => [
                'apiRankA' => 'Maegashira 1 West',
                'apiRankB' => 'Maegashira 1 East',
                'expected' => false,
            ],
            'Same division but different number, a is greater' => [
                'apiRankA' => 'Maegashira 1 West',
                'apiRankB' => 'Maegashira 2 East',
                'expected' => true,
            ],
            'Same division but different number, a is lesser' => [
                'apiRankA' => 'Maegashira 2 West',
                'apiRankB' => 'Maegashira 1 East',
                'expected' => false,
            ],
            'Yokozuna and Maegashira, a greater' => [
                'apiRankA' => 'Yokozuna 1 East',
                'apiRankB' => 'Maegashira 1 East',
                'expected' => true,
            ],
            'Yokozuna and Maegashira, a lesser' => [
                'apiRankA' => 'Maegashira 1 East',
                'apiRankB' => 'Yokozuna 1 East',
                'expected' => false,
            ],
            'Intra-sanyaku, a greater' => [
                'apiRankA' => 'Yokozuna 2 East',
                'apiRankB' => 'Ozeki 1 East',
                'expected' => true,
            ],
            'Intra-sanyaku, a lesser' => [
                'apiRankA' => 'Sekiwake 2 East',
                'apiRankB' => 'Ozeki 1 East',
                'expected' => false,
            ],
            'Different division, a greater' => [
                'apiRankA' => 'Maegashira 1 East',
                'apiRankB' => 'Juryo 1 East',
                'expected' => true,
            ],
            'Different division, a lesser' => [
                'apiRankA' => 'Sandanme 1 East',
                'apiRankB' => 'Juryo 1 East',
                'expected' => false,
            ],
            'Alphanumeric check, a lesser' => [
                'apiRankA' => 'Sandanme 10 East',
                'apiRankB' => 'Sandanme 1 East',
                'expected' => false,
            ],
            'Alphanumeric check, a greater' => [
                'apiRankA' => 'Sandanme 1 East',
                'apiRankB' => 'Sandanme 10 East',
                'expected' => true,
            ],
            'Check that division is the major factor, a greater' => [
                'apiRankA' => 'Jonidan 2 East',
                'apiRankB' => 'Maegashira 1 West',
                'expected' => false,
            ],
            'Check that division is the major factor, a lesser' => [
                'apiRankA' => 'Maegashira 1 West',
                'apiRankB' => 'Jonidan 2 East',
                'expected' => true,
            ],
        ];
    }

    #[Test]
    public function matchesPerBashoSekitori(): void
    {
        $rank = new Rank('Maegashira 1 East');
        $this->assertSame(15, $rank->matchesPerBasho());

        $rank = new Rank('Juryo 1 East');
        $this->assertSame(15, $rank->matchesPerBasho());
    }

    #[Test]
    public function matchesPerBashoLowerRankers(): void
    {
        $rank = new Rank('Makushita 1 East');
        $this->assertSame(7, $rank->matchesPerBasho());
    }
}
