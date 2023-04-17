# Sumo API (PHP)

![Code coverage badge](https://github.com/stuartmcgill/sumoapi-php/blob/image-data/coverage.svg)

This library provides a PHP wrapper for https://sumo-api.com/.

# Usage

See https://sumo-api.com/api-guide for details of the API endpoints.

## Rikishi API

### Sample code

```php
<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiTester;

require __DIR__ . '/../vendor/autoload.php';

use StuartMcGill\SumoApiPhp\Model\Rikishi;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;
use StuartMcGill\SumoApiPhp\Service\RikishiService;

$service = RikishiService::factory();

// Fetch a single rikishi 
$rikishi = $service->fetch(1);
echo $rikishi->nameJp . "\n";

// Fetch all rikishis
$rikishis = $service->fetchAll();
$totalMass = array_reduce(
    array: $rikishis,
    callback: static fn (float $total, Rikishi $rikishi) => $total + $rikishi->weight,
    initial:0,
);
echo "The total mass of all the wrestlers is $totalMass kg\n";

// Fetch all of a rikishi's matches
$matches = $service->fetchMatches(1);
$oshidashiWins = array_filter(
    array: $matches,
    callback: static fn (RikishiMatch $match) =>
            $match->winnerId === 1 && $match->kimarite === 'oshidashi',
);
echo 'Takakeisho has won by Oshidashi ' . count($oshidashiWins) . " times\n";
```

### Output
```
貴景勝　光信
The total mass of all the wrestlers is 79935.7 kg
Takakeisho has won by Oshidashi 190 times
```
