<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Factory;

use stdClass;
use StuartMcGill\SumoApiPhp\Model\Head2Head;

class RikishiFactory
{
    public function build(stdClass $head2HeadData): Head2Head
    {
        return new Head2Head(
            $head2HeadData->rikishiId,
            $head2HeadData->opponentId,
            $head2HeadData->rikishiWins,
            $head2HeadData->opponentWins,
        );
    }
}
