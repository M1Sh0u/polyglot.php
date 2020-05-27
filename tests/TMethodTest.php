<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class TMethodTest extends TestCase
{
    private $phrases = [
        'hello' => 'Hello',
        'hi_name_welcome_to_place' => 'Hi, %{name}, welcome to %{place}!',
        'Hi, %{name}, welcome to %{place}.' => 'Hi, %{name}, welcome to %{place}.',
        'name_your_name_is_name' => '%{name}, your name is %{name}!',
        'empty_string' => '',
        'nav' => [
            'presentations' => 'Presentations',
            'hi_user' => 'Hi, %{user}.',
            'cta' => [
                'join_now' => 'Join now!'
            ]
        ],
        'header.sign_in' => 'Sign In'
    ];

    /**
     * @var Polyglot
     */
    private $polyglot;

    public function setUp(): void
    {
        $this->polyglot = new Polyglot(['phrases' => $this->phrases]);
    }

    public function testTranslatesASimpleString(): void
    {
        $this->assertSame($this->polyglot->t('hello'), 'Hello');
    }

    public function testKeyReturnedIfTranslationNotFound(): void
    {
        $this->assertSame($this->polyglot->t('bogus_key'), 'bogus_key');
    }

    public function testInterpolation(): void
    {
        $this->assertSame(
            $this->polyglot->t('hi_name_welcome_to_place', ['name' => 'Spike', 'place' => 'the webz']),
            'Hi, Spike, welcome to the webz!'
        );
        $this->assertSame(
            $this->polyglot->t('Hi, %{name}, welcome to %{place}.', ['name' => 'Spike', 'place' => 'the webz']),
            'Hi, Spike, welcome to the webz.'
        );
    }

    public function testInterpolatesWithMissingSubstitutions(): void
    {
        $this->assertSame(
            $this->polyglot->t('hi_name_welcome_to_place'),
            'Hi, %{name}, welcome to %{place}!'
        );
        $this->assertSame(
            $this->polyglot->t('hi_name_welcome_to_place', ['place' => null]),
            'Hi, %{name}, welcome to %{place}!'
        );
    }

    public function testInterpolatesTheSameSubstitutionMultipleTimes(): void
    {
        $this->assertSame(
            $this->polyglot->t('name_your_name_is_name', ['name' => 'Spike']),
            'Spike, your name is Spike!'
        );
    }

    public function testAllowsYouToSupplyDefaultValues(): void
    {
        $this->assertSame(
            $this->polyglot->t('can_i_call_you_name', [
                '_' => 'Can I call you %{name}?',
                'name' => 'Mihai'
            ]),
            'Can I call you Mihai?'
        );
    }

    public function testReturnsNonInterpolatedKeyIfNotInitializedWithAllowMissingAndTranslationNotFound(): void
    {
        $this->assertSame(
            $this->polyglot->t('Welcome %{name}', [
                'name' => 'Mihai'
            ]),
            'Welcome %{name}'
        );
    }

    public function testReturnsNonInterpolatedKeyIfInitializedWithAllowMissingAndTranslationNotFound(): void
    {
        $polyglot = new Polyglot(['phrases' => $this->phrases, 'allowMissing' => true]);

        $this->assertSame(
            $polyglot->t('Welcome %{name}', [
                'name' => 'Mihai'
            ]),
            'Welcome Mihai'
        );
    }

    public function testReturnsTranslationIfEmptyString(): void
    {
        $this->assertSame($this->polyglot->t('empty_string'), '');
    }

    public function testReturnsDefaultValueIfEmptyString(): void
    {
        $this->assertSame($this->polyglot->t('bogus_key', ['_' => '']), '');
    }

    public function testHandlesDollarSignsInSubstitutionValue()
    {
        $this->assertSame(
            $this->polyglot->t('hi_name_welcome_to_place', [
                'name' => '$abc $0',
                'place' => '$1 $&'
            ]),
            'Hi, $abc $0, welcome to $1 $&!'
        );
    }

    public function testNestedPhrasesSupport()
    {
        $this->assertSame($this->polyglot->t('nav.presentations'), 'Presentations');
        $this->assertSame($this->polyglot->t('nav.hi_user', ['user' => 'Mihai']), 'Hi, Mihai.');
        $this->assertSame($this->polyglot->t('nav.cta.join_now'), 'Join now!');
        $this->assertSame($this->polyglot->t('header.sign_in'), 'Sign In');
    }

    public function testOnMissingKeyOption()
    {
        $expectedKey = 'some key';
        $expectedOptions = [];
        $expectedLocale = 'oz';
        $returnValue = 'some returned value';

        $onMissingKey = function($key, $options, $locale, $tokenRegex, $polyglot) use ($expectedKey, $expectedOptions, $expectedLocale, $returnValue) {
            $this->assertSame($key, $expectedKey);
            $this->assertSame($options, $expectedOptions);
            $this->assertSame($locale, $expectedLocale);
            $this->assertInstanceOf(Polyglot::class, $polyglot);

            return $returnValue;
        };

        $polyglot = new Polyglot(['onMissingKey' => $onMissingKey, 'locale' => $expectedLocale]);
        $result = $polyglot->t($expectedKey, $expectedOptions);

        $this->assertSame($returnValue, $result);
    }

    public function testOverridesAllowMissing()
    {
        $missingKey = 'missing key';

        $onMissingKey = function($key) use ($missingKey) {
            $this->assertSame($key, $missingKey);
            return '';
        };

        $polyglot = new Polyglot(['allowMissing' => true, 'onMissingKey' => $onMissingKey]);
        $polyglot->t($missingKey);
    }
}