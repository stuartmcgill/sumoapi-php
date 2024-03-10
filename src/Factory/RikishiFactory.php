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
            $rikishiData->shikonaEn,
            $rikishiData->sumodbId ?? null,
            $rikishiData->nskId ?? null,
            $rikishiData->shikonaJp ?? null,
            $rikishiData->currentRank ?? null,
            $rikishiData->heya ?? null,
            isset($rikishiData->birthDate) ? new DateTime($rikishiData->birthDate) : null,
            $rikishiData->shusshin ?? null,
            $rikishiData->height ?? null,
            $rikishiData->weight ?? null,
            $rikishiData->debut ?? null,
        );
    }
}
