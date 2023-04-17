<?php

declare(strict_types=1);

class Rikishi
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}
