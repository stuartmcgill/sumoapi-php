<?php

declare(strict_types=1);

namespace Unit\Model;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;

class RikishiMatchTest extends TestCase
{
    #[Test]
    public function rikishiIdentities(): void
    {
        $match = $this->generateMatch(eastId: 1, westId: 2, winnerId: 1);

        $this->assertSame(true, $match->isWinner(1));
        $this->assertSame(false, $match->isWinner(2));

        $this->assertSame(true, $match->isEast(1));
        $this->assertSame(false, $match->isEast(2));

        $this->assertSame(false, $match->isWest(1));
        $this->assertSame(true, $match->isWest(2));
    }

    private function generateMatch(
        ?int $eastId,
        ?int $westId,
        ?int $winnerId,
    ): RikishiMatch {
        return new RikishiMatch(
            bashoId: '',
            division: '',
            day: 1,
            eastId: $eastId,
            eastShikona: '',
            eastRank: '',
            westId: $westId,
            westShikona: '',
            westRank: '',
            kimarite: '',
            winnerId: $winnerId,
            winnerEn: '',
            winnerJp: '',
        );
    }
}
