<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Factory;

use stdClass;
use StuartMcGill\SumoApiPhp\Model\Matchup;

class MatchupFactory
{
    public function build(stdClass $matchupData): Matchup
    {
        return new Matchup(
            $matchupData->rikishiId,
            $matchupData->opponentId,
            $matchupData->rikishiWins,
            $matchupData->opponentWins,
        );
    }
}
