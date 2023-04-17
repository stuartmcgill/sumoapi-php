<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

use DateTime;

class Rikishi
{
    public function __construct(
        public readonly int $id,
        public readonly int $sumoDbId,
        public readonly int $nskId,
        public readonly string $name,
        public readonly string $nameJp,
        public readonly string $rank,
        public readonly string $heya,
        public readonly DateTime $dateOfBirth,
        public readonly string $shusshin,
        public readonly int $height,
        public readonly int $weight,
        public readonly string $debut,
    ) {
    }
}
