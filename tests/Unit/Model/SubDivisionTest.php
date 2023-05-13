<?php

declare(strict_types=1);

namespace Unit\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Model\SubDivision;

class SubDivisionTest extends TestCase
{
    #[DataProvider('isGreaterThanProvider')]
    #[Test]
    public function isGreaterThan(string $a, string $b, bool $expected): void
    {
        $divisionA = new SubDivision($a);
        $divisionB = new SubDivision($b);

        $this->assertSame($expected, $divisionA->isGreaterThan($divisionB));
    }

    /** @return array<string, array<string, mixed>> */
    public static function isGreaterThanProvider(): array
    {
        return [
            'Same' => [
                'a' => 'Yokozuna',
                'b' => 'Yokozuna',
                'expected' => false,
            ],
            'a greater' => [
                'a' => 'Yokozuna',
                'b' => 'Ozeki',
                'expected' => true,
            ],
            'a lesser' => [
                'a' => 'Ozeki',
                'b' => 'Yokozuna',
                'expected' => false,
            ],
        ];
    }

    #[Test]
    public function isMakuuchi(): void
    {
        $division = new SubDivision('Maegashira');
        $this->assertTrue($division->isMakuuchi());

        $division = new SubDivision('Yokozuna');
        $this->assertTrue($division->isMakuuchi());

        $division = new SubDivision('Juryo');
        $this->assertFalse($division->isMakuuchi());
    }
}
