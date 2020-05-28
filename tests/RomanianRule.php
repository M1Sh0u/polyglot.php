<?php

declare(strict_types=1);

use Polyglot\Pluralization\Rules\RuleInterface;

class RomanianRule implements RuleInterface
{
    /**
     * Decide which part of the phrase to be returned when pluralizing depending on the current locale.
     *
     * @param int $n
     *
     * @return int
     */
    public function decide(int $n): int
    {
        return $n !== 1 ? 1 : 0;
    }
}