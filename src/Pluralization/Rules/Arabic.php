<?php

declare(strict_types=1);

namespace MihaiMATEI\Polyglot\Pluralization\Rules;

/**
 * @package Polyglot/Pluralization/Rules
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
class Arabic implements RuleInterface
{
    /**
     * @inheritDoc
     */
    public function decide(int $n): int
    {
        // http://www.arabeyes.org/Plural_Forms
        if ($n < 3) {
            return $n;
        }

        $lastTwo = $n % 100;
        if ($lastTwo >= 3 && $lastTwo <= 10) {
            return 3;
        }

        return $lastTwo >= 11 ? 4 : 5;
    }
}