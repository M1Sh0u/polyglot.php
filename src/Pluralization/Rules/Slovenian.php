<?php

declare(strict_types=1);

namespace Polyglot\Pluralization\Rules;

/**
 * @package Polyglot/Pluralization/Rules
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
class Slovenian implements RuleInterface
{
    /**
     * @inheritDoc
     */
    public function decide(int $n): int
    {
        $lastTwo = $n % 100;

        if ($lastTwo === 1) {
            return 0;
        }

        if ($lastTwo === 2) {
            return 1;
        }

        if ($lastTwo === 3 || $lastTwo === 4) {
            return 2;
        }

        return 3;
    }
}