<?php

declare(strict_types=1);

namespace Unit\Factory;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use StuartMcGill\SumoApiPhp\Factory\MatchupFactory;

class MatchupFactoryTest extends TestCase
{
    #[Test]
    public function build(): void
    {
        // Takakeisho 5-4 v. Asanoyama as of July 2023
        $json = json_decode(file_get_contents(__DIR__ . '/../../_data/matchup_1_2.json'));

        $factory = new MatchupFactory();
        $matchup = $factory->build(1, 2, $json);

        $this->assertSame(1, $matchup->rikishiId);
        $this->assertSame(2, $matchup->opponentId);
        $this->assertSame(5, $matchup->rikishiWins);
        $this->assertSame(4, $matchup->opponentWins);
    }
}
