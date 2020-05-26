<?php

declare(strict_types=1);

namespace Polyglot\Pluralization\Rules;

/**
 * @package Polyglot/Pluralization/Rules
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
class Czech implements RuleInterface
{
    /**
     * @inheritDoc
     */
    public function decide(int $n): int
    {
        if ($n === 1) {
            return 0;
        }

        return ($n >= 2 && $n <= 4) ? 1 : 2;
    }
}