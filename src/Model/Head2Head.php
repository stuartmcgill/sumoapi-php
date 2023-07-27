<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

class Head2Head
{
    public function __construct(
        public readonly int $rikishiId,
        public readonly int $opponentId,
        public readonly int $rikishiWins,
        public readonly int $opponentWins,
    ) {
    }

    public function total(): int
    {
        return $this->rikishiWins + $this->opponentWins;
    }

    public function winningPercentage(): ?int
    {
        if ($this->total() === 0) {
            return null;
        }

        return (int)round($this->rikishiWins / $this->total() * 100);
    }
}
