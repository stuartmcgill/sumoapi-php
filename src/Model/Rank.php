<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

class Rank
{
    public function __construct(private readonly string $apiRank)
    {
    }

    public function division(): string
    {
        if ($this->isMakuuchi()) {
            return 'Makuuchi';
        }

        $lowerDivision = $this->findLowerDivision();

        return $lowerDivision ?? $this->apiRank;
    }

    private function isMakuuchi(): bool
    {
        $makuuchiRanks = [
            'Yokozuna',
            'Ozeki',
            'Sekiwake',
            'Komusubi',
            'Maegashira',
        ];

        return count(array_filter(
            array: $makuuchiRanks,
            callback: fn (string $makuuchiRank)
            => str_contains(haystack: $this->apiRank, needle: $makuuchiRank),
        )) > 0;
    }

    private function findLowerDivision(): ?string
    {
        $config = include __DIR__ . '/../../config/config.php';
        $divisions = $config['divisions'];

        foreach ($divisions as $division) {
            if (str_contains(haystack: $this->apiRank, needle: $division)) {
                return $division;
            }
        }

        return null;
    }
}
