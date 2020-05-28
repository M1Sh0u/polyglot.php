<?php

declare(strict_types=1);

require_once 'RomanianRule.php';

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class CustomPluralRulesTest extends TestCase
{
    private $phrases = [
        'num_cars' => '%{smart_count} mașină |||| %{smart_count} mașini',
    ];

    public function testCustomPluralRuleIsUsed(): void
    {
        $polyglot = new Polyglot([
            'phrases' => $this->phrases,
            'locale' => 'ro',
            'pluralRules' => ['ro' => new RomanianRule()]
        ]);

        $this->assertSame(
            $polyglot->t('num_cars', ['smart_count' => 0]),
            '0 mașini'
        );
        $this->assertSame(
            $polyglot->t('num_cars', ['smart_count' => 1]),
            '1 mașină'
        );
        $this->assertSame(
            $polyglot->t('num_cars', ['smart_count' => 2]),
            '2 mașini'
        );
        $this->assertSame(
            $polyglot->t('num_cars', ['smart_count' => 3]),
            '3 mașini'
        );
    }

    public function testCustomPluralRuleIsNotUsedWhenDifferentLocale(): void
    {
        $polyglot = new Polyglot([
            'phrases' => $this->phrases,
            'locale' => 'en',
            'pluralRules' => ['ro' => new RomanianRule()]
        ]);

        $this->assertSame(
            $polyglot->t('num_cars', ['smart_count' => 0]),
            '0 mașini'
        );
        $this->assertSame(
            $polyglot->t('num_cars', ['smart_count' => 1]),
            '1 mașină'
        );
        $this->assertSame(
            $polyglot->t('num_cars', ['smart_count' => 2]),
            '2 mașini'
        );
        $this->assertSame(
            $polyglot->t('num_cars', ['smart_count' => 3]),
            '3 mașini'
        );
    }

    public function testExceptionThrownIfPluralRuleOtherThanObjectIsUsed(): void
    {
        $this->expectException(RuntimeException::class);

        $polyglot = new Polyglot([
            'phrases' => $this->phrases,
            'locale' => 'ro',
            'pluralRules' => ['ro' => 'test']
        ]);

        $polyglot->t('num_cars', ['smart_count' => 3]);
    }

    public function testExceptionThrownIfPluralRuleObjectDoesNotImplementRuleInterface(): void
    {
        $this->expectException(RuntimeException::class);

        $polyglot = new Polyglot([
            'phrases' => $this->phrases,
            'locale' => 'ro',
            'pluralRules' => ['ro' => new stdClass()]
        ]);

        $polyglot->t('num_cars', ['smart_count' => 3]);
    }
}