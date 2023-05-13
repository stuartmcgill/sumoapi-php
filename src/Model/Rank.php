<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

class Rank
{
    private const MAKUUCHI_SUB_DIVISIONS = [
        'Yokozuna',
        'Ozeki',
        'Sekiwake',
        'Komusubi',
        'Maegashira',
    ];

    public function __construct(private readonly string $apiRank)
    {
    }

    public function division(): string
    {
        if ($this->isMakuuchi()) {
            return 'Makuuchi';
        }

        return $this->findLowerDivision() ?? $this->apiRank;
    }

    /** Division 1 (Makuuchi) is the highest division, so we use > checks rather than < */
    public function isLessThan(Rank $other): bool
    {
        $thisDivisionNumber = $this->divisionNumber();
        $otherDivisionNumber = $other->divisionNumber();

        if ($thisDivisionNumber !== $otherDivisionNumber) {
            return $thisDivisionNumber > $otherDivisionNumber;
        }

        if ($this->isMakuuchi()) {
            $thisSubDivisionNumber = $this->getMakuuchiSubDivisionNumber();
            $otherSubDivisionNumber = $other->getMakuuchiSubDivisionNumber();

            if ($thisSubDivisionNumber !== $otherSubDivisionNumber) {
                return $thisSubDivisionNumber > $otherSubDivisionNumber;
            }
        }

        return $this->apiRank > $other->apiRank;
    }

    private function divisionNumber(): ?int
    {
        if ($this->isMakuuchi()) {
            return 1;
        }

        foreach ($this->getDivisions() as $key => $division) {
            if (str_contains(haystack: $this->apiRank, needle: $division)) {
                return $key + 1;
            }
        }

        return null;
    }

    private function isMakuuchi(): bool
    {
        return count(array_filter(
            array: self::MAKUUCHI_SUB_DIVISIONS,
            callback: fn (string $makuuchiRank)
            => str_contains(haystack: $this->apiRank, needle: $makuuchiRank),
        )) > 0;
    }

    private function findLowerDivision(): ?string
    {
        foreach ($this->getDivisions() as $division) {
            if (str_contains(haystack: $this->apiRank, needle: $division)) {
                return $division;
            }
        }

        return null;
    }

    /** @return list<string> */
    private function getDivisions(): array
    {
        $config = include __DIR__ . '/../../config/config.php';

        return $config['divisions'];
    }

    private function getMakuuchiSubDivisionNumber(): ?int
    {
        foreach (self::MAKUUCHI_SUB_DIVISIONS as $key => $makuuchiSubDivision) {
            if (str_contains(haystack: $this->apiRank, needle: $makuuchiSubDivision)) {
                return $key;
            }
        }

        return null;
    }
}
