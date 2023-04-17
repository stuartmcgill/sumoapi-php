<?php

declare(strict_types=1);

namespace Unit\Factory;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Factory\RikishiFactory;

class RikishiFactoryTest extends TestCase
{
    #[Test]
    public function build(): void
    {
        $json = json_decode(file_get_contents(__DIR__ . '/../../_data/rikishi.json'));

        $factory = new RikishiFactory();
        $rikishi = $factory->build($json);

        $this->assertSame(1, $rikishi->id);
        $this->assertSame('Takakeisho', $rikishi->name);
        $this->assertSame('貴景勝　光信', $rikishi->nameJp);
        $this->assertSame('1996-08-05', $rikishi->dateOfBirth->format('Y-m-d'));
    }
}
