<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

class Head2Head
{
    public function __construct(
        public readonly int $id,
        public readonly int $opponent,
        public readonly int $wins,
        public readonly int $losses,
    ) {
    }
}
