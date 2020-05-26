<?php

declare(strict_types=1);

namespace MihaiMATEI\Polyglot\Pluralization\Rules;

/**
 * @package Polyglot/Pluralization/Rules
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
class Polish implements RuleInterface
{
    /**
     * @inheritDoc
     */
    public function decide(int $n): int
    {
        if ($n === 1) {
            return 0;
        }

        $end = $n % 10;

        return 2 <= $end && $end <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2;
    }
}