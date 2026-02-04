<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

use DateTime;

readonly class Rikishi
{
    public function __construct(
        public int $id,
        public ?string $shikonaEn,
        public ?int $sumoDbId,
        public ?int $nskId,
        public ?string $shikonaJp,
        public ?string $currentRank,
        public ?string $heya,
        public ?DateTime $birthDate,
        public ?string $shusshin,
        public ?float $height,
        public ?float $weight,
        public ?string $debut,
    ) {
    }
}
