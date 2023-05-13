<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiPhp\Model;

use DomainException;

class SubDivision
{
    private const RANKING = [
        'Yokozuna',
        'Ozeki',
        'Sekiwake',
        'Komusubi',
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