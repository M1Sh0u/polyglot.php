<?php

declare(strict_types=1);

namespace Polyglot\Pluralization\Rules;

/**
 * @package Polyglot/Pluralization/Rules
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
class Icelandic implements RuleInterface
{
    /**
     * @inheritDoc
     */
    public function decide(int $n): int
    {
        return ($n % 10 !== 1 || $n % 100 === 11) ? 1 : 0;
    }
}