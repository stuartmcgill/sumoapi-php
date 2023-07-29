<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Factory;

use stdClass;
use StuartMcGill\SumoApiPhp\Model\Matchup;

class MatchupFactory
{
    public function build(int $rikishiId, int $opponentId, stdClass $matchupData): Matchup
    {
        return new Matchup(
            $rikishiId,
            $opponentId,
            $matchupData->rikishiWins,
            $matchupData->opponentWins,
        );
    }
}
