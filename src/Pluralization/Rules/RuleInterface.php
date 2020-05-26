<?php

declare(strict_types=1);

namespace MihaiMATEI\Polyglot\Pluralization\Rules;

/**
 * A Rule interface to be implemented by all the existing pluralization rules.
 *
 * @package Polyglot/Pluralization/Rules
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
interface RuleInterface
{
    /**
     * Decide which part of the phrase to be returned when pluralizing depending on the current locale.
     *
     * @param int $n
     *
     * @return int
     */
    public function decide(int $n): int;
}