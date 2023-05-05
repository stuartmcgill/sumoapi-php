<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

class RikishiMatch
{
    public function __construct(
        public readonly string $bashoId,
        public readonly string $division,
        public readonly int $day,
        public readonly int $eastId,
        public readonly string $eastShikona,
        public readonly string $eastRank,
        public readonly int $westId,
        public readonly string $westShikona,
        public readonly string $westRank,
        public readonly string $kimarite,
        public readonly int $winnerId,
        public readonly string $winnerEn,
        public readonly string $winnerJp,
    ) {
    }
}
