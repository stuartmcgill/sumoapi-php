<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

use stdClass;

class MatchupSummary
{
    /** @param list<Matchup> $matchups */
    public function __construct(
        public readonly int $id,
        public readonly array $matchups,
    ) {
    }

    public static function build(stdClass $json): self
    {
        return new self(

        );
    }
}
