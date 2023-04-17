#!/bin/env php
<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp;

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use StuartMcGill\SumoApiPhp\Service\RikishiService;

$service = new RikishiService(new Client());
$rikishi = $service->fetch(1);

echo $rikishi->nameJp;
