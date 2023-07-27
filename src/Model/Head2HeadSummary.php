<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

use stdClass;

class Head2HeadSummary
{
    /** @param list<Head2Head> $head2Heads */
    public function __construct(
        public readonly int $id,
        public readonly array $head2Heads,
    ) {
    }

    public static function build(stdClass $json): self
    {
        return new self(

        );
    }
}
