<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

use DomainException;

class SubDivision
{
    // Makuuchi is included to enable the ranking of divisions as well as rikishi
    private const RANKING = [
        'Yokozuna',
        'Ozeki',
        'Sekiwake',
        'Komusubi',
        'Makuuchi',
        'Maegashira',
        'Juryo',
        'Makushita',
        'Sandanme',
        'Jonidan',
        'Jonokuchi',
        'Banzuke-gai',
    ];

    private const MAKUUCHI_SUB_DIVISIONS = [
        'Yokozuna',
        'Ozeki',
        'Sekiwake',
        'Komusubi',
        'Maegashira',
    ];

    public function __construct(public readonly string $name)
    {
    }

    public function isMakuuchi(): bool
    {
        return in_array(
            needle: $this->name,
            haystack: self::MAKUUCHI_SUB_DIVISIONS,
            strict: true,
        );
    }

    public function isSekitori(): bool
    {
        return $this->isGreaterThan(new SubDivision('Makushita'));
    }

    public function isGreaterThan(SubDivision $other): bool
    {
        if ($this->name === $other->name) {
            return false;
        }

        foreach (self::RANKING as $ranking) {
            if ($ranking === $this->name) {
                return true;
            }
            if ($ranking === $other->name) {
                return false;
            }
        }

        throw new DomainException(
            'Unexpected values for this: ' . $this->name . ' and other: ' . $other->name
        );
    }
}
