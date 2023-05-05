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
        $json = json_decode(file_get_contents(__DIR__ . '/../../_data/rikishi_1.json'));

        $factory = new RikishiFactory();
        $rikishi = $factory->build($json);

        $this->assertSame(1, $rikishi->id);
        $this->assertSame('Takakeisho', $rikishi->shikonaEn);
        $this->assertSame('貴景勝　光信', $rikishi->shikonaJp);
        $this->assertSame('1996-08-05', $rikishi->birthDate->format('Y-m-d'));
    }

    #[Test]
    /** When wrestlers are first added to the API they may have less data than normal */
    public function buildNewStarter(): void
    {
        $json = json_decode(file_get_contents(__DIR__ . '/../../_data/rikishi_new_starter.json'));

        $factory = new RikishiFactory();
        $rikishi = $factory->build($json);

        $this->assertSame(8846, $rikishi->id);
        $this->assertSame('Daishoheki', $rikishi->shikonaEn);
    }
}
