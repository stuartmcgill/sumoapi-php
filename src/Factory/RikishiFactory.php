<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Factory;

use DateTime;
use stdClass;
use StuartMcGill\SumoApiPhp\Model\Rikishi;

class RikishiFactory
{
    public function build(stdClass $rikishiData): Rikishi
    {
        return new Rikishi(
            $rikishiData->id,
            $rikishiData->sumodbId ?? null,
            $rikishiData->nskId ?? null,
            $rikishiData->shikonaEn,
            $rikishiData->shikonaJp ?? null,
            $rikishiData->currentRank,
            $rikishiData->heya,
            new DateTime($rikishiData->birthDate),
            $rikishiData->shusshin,
            $rikishiData->height,
            $rikishiData->weight,
            $rikishiData->debut,
        );
    }
}
