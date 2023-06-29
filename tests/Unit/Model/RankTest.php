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

    /** @return array<string, array<string, string>> */
    public static function isGreaterThanProvider(): array
    {
        return [
            'Same' => [
                'a' => 'Maegashira 1 East',
                'b' => 'Maegashira 1 East',
                'expected' => false,
            ],
            'Same division and number, a is greater' => [
                'a' => 'Maegashira 1 East',
                'b' => 'Maegashira 1 West',
                'expected' => true,
            ],
            'Same division and number, a is lesser' => [
                'a' => 'Maegashira 1 West',
                'b' => 'Maegashira 1 East',
                'expected' => false,
            ],
            'Same division but different number, a is greater' => [
                'a' => 'Maegashira 1 West',
                'b' => 'Maegashira 2 East',
                'expected' => true,
            ],
            'Same division but different number, a is lesser' => [
                'a' => 'Maegashira 2 West',
                'b' => 'Maegashira 1 East',
                'expected' => false,
            ],
            'Yokozuna and Maegashira, a greater' => [
                'a' => 'Yokozuna 1 East',
                'b' => 'Maegashira 1 East',
                'expected' => true,
            ],
            'Yokozuna and Maegashira, a lesser' => [
                'a' => 'Maegashira 1 East',
                'b' => 'Yokozuna 1 East',
                'expected' => false,
            ],
            'Intra-sanyaku, a greater' => [
                'a' => 'Yokozuna 2 East',
                'b' => 'Ozeki 1 East',
                'expected' => true,
            ],
            'Intra-sanyaku, a lesser' => [
                'a' => 'Sekiwake 2 East',
                'b' => 'Ozeki 1 East',
                'expected' => false,
            ],
            'Different division, a greater' => [
                'a' => 'Maegashira 1 East',
                'b' => 'Juryo 1 East',
                'expected' => true,
            ],
            'Different division, a lesser' => [
                'a' => 'Sandanme 1 East',
                'b' => 'Juryo 1 East',
                'expected' => false,
            ],
            'Alphanumeric check, a lesser' => [
                'a' => 'Sandanme 10 East',
                'b' => 'Sandanme 1 East',
                'expected' => false,
            ],
            'Alphanumeric check, a greater' => [
                'a' => 'Sandanme 1 East',
                'b' => 'Sandanme 10 East',
                'expected' => true,
            ],
            'Check that division is the major factor, a greater' => [
                'a' => 'Jonidan 2 East',
                'b' => 'Maegashira 1 West',
                'expected' => false,
            ],
            'Check that division is the major factor, a lesser' => [
                'a' => 'Maegashira 1 West',
                'b' => 'Jonidan 2 East',
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
