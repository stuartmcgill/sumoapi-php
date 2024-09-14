# Sumo API (PHP)

![Code coverage badge](https://github.com/stuartmcgill/sumoapi-php/blob/image-data/coverage.svg)

This library provides a PHP wrapper for https://sumo-api.com/. Currently the following functionality
is available:

- Fetch all rikishis
- Fetch a rikishi by ID
- Fetch a rikishi's matches
- Fetch multiple rikishi (by IDs)
- Fetch multiple rikishi (by division)
- Fetch rikishi matchups
- Fetch kimarite (by type)

# Installation

`composer require stuartmcgill/sumoapi-php`

# Usage

See https://sumo-api.com/api-guide for details of the API endpoints.

## Rikishi API

### Sample code

```php
#!/bin/env php
<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiTester;

require __DIR__ . '/../vendor/autoload.php';

use StuartMcGill\SumoApiPhp\Model\Rikishi;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;
use StuartMcGill\SumoApiPhp\Service\BashoService;
use StuartMcGill\SumoApiPhp\Service\RikishiService;

$bashoService = BashoService::factory();
$rikishiService = RikishiService::factory();

// Fetch rikishis from a particular basho
$rikishisFromThePast = $bashoService->fetchRikishiIdsByBasho(2019, 3, 'Makuuchi');
echo 'Rikishi IDs from March 2019 are ' . implode(',', $rikishisFromThePast) . "\n";

// Fetch a single rikishi
$rikishi = $rikishiService->fetch(1);
echo $rikishi->shikonaJp . "\n";

// Fetch all rikishis
$rikishis = $rikishiService->fetchAll();
$totalMass = array_reduce(
    array: $rikishis,
    callback: static fn (float $total, Rikishi $rikishi) => $total + $rikishi->weight,
    initial:0,
);
echo "The total mass of all the wrestlers is $totalMass kg\n";

// Fetch all of a rikishi's matches
$matches = $rikishiService->fetchMatches(1);
$oshidashiWins = array_filter(
    array: $matches,
    callback: static fn (RikishiMatch $match) =>
            $match->winnerId === 1 && $match->kimarite === 'oshidashi',
);
echo 'Takakeisho has won by Oshidashi ' . count($oshidashiWins) . " times\n";

// Fetch some rikishi (by IDs)
$someRikishi = $rikishiService->fetchSome([1, 2]);
echo 'Fetched details for ' . count($someRikishi) . " wrestlers\n";

// Fetch rikishi and filter by division
$someRikishi = $rikishiService->fetchDivision('Makuuchi');
echo 'Fetched details for ' . count($someRikishi) . " Makuuchi wrestlers\n";

// Fetch rikishi matchups (head-to-heads)
$matchupSummary = $rikishiService->fetchMatchups(1, [2]);
echo 'Takakeisho has fought Asanoyama ' . $matchupSummary->matchups[0]->total() . ' times';
```

### Output
```
Rikishi IDs from March 2019 are 3081,44,43,1,26,674,9,16,3195,637,47,23,2,35,673,15,51,102,67,368,48,39,3181,3249,14,27,17,36,33,46,10,22,25,3142,3120,106,38,3248,29,3204,30,636
貴景勝　光信
The total mass of all the wrestlers is 83279.2 kg
Takakeisho has won by Oshidashi 193 times
Fetched details for 2 wrestlers
Fetched details for 42 Makuuchi wrestlers
Takakeisho has fought Asanoyama 9 times⏎   
```

## Kimarite API

### Sample code

```php
#!/bin/env php
<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiTester;

require __DIR__ . '/../vendor/autoload.php';

use StuartMcGill\SumoApiPhp\Service\KimariteService;

$kimariteService = KimariteService::factory();

// Fetch last three matches where the kimarite was yorikiri
$matches = $kimariteService->fetchByType(type: 'yorikiri', sortOrder: 'desc', limit: 3, skip: 0);
foreach ($matches as $match) {
    $loser = $match->loserEn();
    echo "$match->winnerEn defeated $loser by yorikiri in $match->division "
        . "on day $match->day of the $match->bashoId basho.\n";
}
```

### Output