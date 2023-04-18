<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

use DateTime;

class Rikishi
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $sumoDbId,
        public readonly ?int $nskId,
        public readonly string $shikonaEn,
        public readonly ?string $shikonaJp,
        public readonly string $currentRank,
        public readonly string $heya,
        public readonly DateTime $birthDate,
        public readonly string $shusshin,
        public readonly float $height,
        public readonly float $weight,
        public readonly string $debut,
    ) {
    }
}
