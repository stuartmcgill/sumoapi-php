<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

class MatchupSummary
{
    /** @param list<Matchup> $matchups */
    public function __construct(
        public readonly int $rikishiId,
        public readonly array $matchups,
    ) {
    }
}
