<?php

declare(strict_types=1);

namespace Unit\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Model\Head2Head;

class Head2HeadTest extends TestCase
{
    #[Test]
    public function winningPercentageNeverMet(): void
    {
        $head2Head = new Head2Head(
            rikishiId: 1,
            opponentId: 2,
            rikishiWins: 0,
            opponentWins: 0,
        );
        $this->assertSame(null, $head2Head->winningPercentage());
    }

    #[DataProvider('winningPercentageProvider')]
    #[Test]
    public function winningPercentage(int $rikishiWins, int $opponentWins, int $expected): void
    {
        $head2Head = new Head2Head(
            rikishiId: 1,
            opponentId: 2,
            rikishiWins: $rikishiWins,
            opponentWins: $opponentWins,
        );
        $this->assertSame($expected, $head2Head->winningPercentage());
    }

    /** @return array<string, array<string, int>> */
    public static function winningPercentageProvider(): array
    {
        return [
            'All wins' => [
                'rikishiWins' => 1,
                'opponentWins' => 0,
                'expected' => 100,
            ],
            'All losses' => [
                'rikishiWins' => 0,
                'opponentWins' => 1,
                'expected' => 0,
            ],
            'Mixed' => [
                'rikishiWins' => 1,
                'opponentWins' => 2,
                'expected' => 33,
            ],
        ];
    }
}
