<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

class Rank
{
    public readonly SubDivision $subDivision;
    public readonly int $number;
    public readonly string $side;

    public function __construct(private readonly string $apiRank)
    {
        $matches = [];

        !preg_match(
            pattern: '/^([[:alpha:]]+) ([\d]{1,3}) (East|West)$/',
            subject: $this->apiRank,
            matches: $matches,
        );

        // We always want at least a sub-division
        $this->subDivision = new SubDivision($matches[1] ?? $this->apiRank);
        if (count($matches) === 0) {
            return;
        }

        $this->number = (int)$matches[2];
        $this->side = $matches[3];
    }

    public function division(): string
    {
        if ($this->subDivision->isMakuuchi()) {
            return 'Makuuchi';
        }

        return $this->subDivision->name;
    }

    public function isGreaterThan(Rank $other): bool
    {
        if ($this->subDivision->name !== $other->subDivision->name) {
            return $this->subDivision->isGreaterThan($other->subDivision);
        }

        if ($this->number !== $other->number) {
            // The lower number (e.g. M1) is the higher rank
            return $this->number < $other->number;
        }

        // East is higher than West
        return $this->side < $other->side;
    }
}
