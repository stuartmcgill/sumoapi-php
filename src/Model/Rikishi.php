<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

class Rikishi
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}
