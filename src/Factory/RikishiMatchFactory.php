<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Factory;

use stdClass;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;

class RikishiMatchFactory
{
    public function build(stdClass $rikishiMatchData): RikishiMatch
    {
        return new RikishiMatch(
            $rikishiMatchData->bashoId,
            $rikishiMatchData->division,
            $rikishiMatchData->day,
            $rikishiMatchData->eastId,
            $rikishiMatchData->eastShikona,
            $rikishiMatchData->eastRank,
            $rikishiMatchData->westId,
            $rikishiMatchData->westShikona,
            $rikishiMatchData->westRank,
            $rikishiMatchData->kimarite,
            $rikishiMatchData->winnerId,
            $rikishiMatchData->winnerEn,
            $rikishiMatchData->winnerJp,
        );
    }
}
