<?php

declare(strict_types=1);

namespace Unit\Model;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Model\Rikishi;

class RikishiTest extends TestCase
{
    #[Test]
    public function create(): void
    {
        $rikishi = new Rikishi(1, 'Hakuho');
        $this->assertSame(1, $rikishi->id);
    }
}
