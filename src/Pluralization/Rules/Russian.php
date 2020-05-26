<?php

declare(strict_types=1);

namespace MihaiMATEI\Polyglot\Pluralization\Rules;

/**
 * @package Polyglot/Pluralization/Rules
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
class Russian implements RuleInterface
{
    /**
     * @inheritDoc
     */
    public function decide(int $n): int
    {
        $lastTwo = $n % 100;
        $end = $lastTwo % 10;

        if ($lastTwo !== 11 && $end === 1) {
            return 0;
        }
        if (2 <= $end && $end <= 4 && !($lastTwo >= 12 && $lastTwo <= 14)) {
            return 1;
        }
        return 2;
    }
}