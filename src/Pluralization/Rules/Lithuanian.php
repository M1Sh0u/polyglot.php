<?php

declare(strict_types=1);

namespace MihaiMATEI\Polyglot\Pluralization\Rules;

/**
 * @package Polyglot/Pluralization/Rules
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
class Lithuanian implements RuleInterface
{
    /**
     * @inheritDoc
     */
    public function decide(int $n): int
    {
        if ($n % 10 === 1 && $n % 100 !== 11) {
            return 0;
        }

        return $n % 10 >= 2 && $n % 10 <= 9 && ($n % 100 < 11 || $n % 100 > 19) ? 1 : 2;
    }
}