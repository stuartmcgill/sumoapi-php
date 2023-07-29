<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

use JsonSerializable;

class Matchup implements JsonSerializable
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

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return [
            'rikishiId' => $this->rikishiId,
            'opponentId' => $this->opponentId,
            'rikishiWins' => $this->rikishiWins,
            'opponentWins' => $this->opponentWins,
            'winningPercentage' => $this->winningPercentage(),
        ];
    }
}
