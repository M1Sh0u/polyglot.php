<?php

declare(strict_types=1);

namespace Polyglot\Pluralization\Rules;

/**
 * @package Polyglot/Pluralization/Rules
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
class Chinese implements RuleInterface
{
    /**
     * @inheritDoc
     */
    public function decide(int $n): int
    {
        return 0;
    }
}