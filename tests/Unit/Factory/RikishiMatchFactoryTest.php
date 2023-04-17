<?php

declare(strict_types=1);

namespace Unit\Factory;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Factory\RikishiMatchFactory;

class RikishiMatchFactoryTest extends TestCase
{
    #[Test]
    public function build(): void
    {
        $json = json_decode(file_get_contents(__DIR__ . '/../../_data/rikishiMatch.json'));

        $factory = new RikishiMatchFactory();
        $match = $factory->build($json);

        $this->assertSame('202303', $match->bashoId);
        $this->assertSame('Takakeisho', $match->winnerEn);
        $this->assertSame('貴景勝　光信', $match->winnerJp);
    }
}
