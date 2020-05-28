<?php

declare(strict_types=1);

namespace Polyglot\Pluralization;

use Polyglot\Pluralization\Rules\Arabic;
use Polyglot\Pluralization\Rules\BosnianSerbian;
use Polyglot\Pluralization\Rules\Chinese;
use Polyglot\Pluralization\Rules\Croatian;
use Polyglot\Pluralization\Rules\Czech;
use Polyglot\Pluralization\Rules\French;
use Polyglot\Pluralization\Rules\German;
use Polyglot\Pluralization\Rules\Icelandic;
use Polyglot\Pluralization\Rules\Lithuanian;
use Polyglot\Pluralization\Rules\Polish;
use Polyglot\Pluralization\Rules\RuleInterface;
use Polyglot\Pluralization\Rules\Russian;
use Polyglot\Pluralization\Rules\Slovenian;
use RuntimeException;

/**
 * Rule factory which crafts a pluralization rule depending on the provided locale.
 *
 * @package Polyglot/Pluralization
 * @author  Mihai MATEI <mihai.matei@smartbridge.ch>
 */
class RuleFactory
{
    /**
     * @var array
     */
    private static $localesRulesMapping = [
        'ar' => Arabic::class,
        'bs-Latn-BA' => BosnianSerbian::class,
        'bs-Cyrl-BA' => BosnianSerbian::class,
        'srl-RS' => BosnianSerbian::class,
        'sr-RS' => BosnianSerbian::class,
        'id' => Chinese::class,
        'id-ID' => Chinese::class,
        'ja' => Chinese::class,
        'ko' => Chinese::class,
        'ko-KR' => Chinese::class,
        'lo' => Chinese::class,
        'ms' => Chinese::class,
        'th' => Chinese::class,
        'th-TH' => Chinese::class,
        'zh' => Chinese::class,
        'hr' => Croatian::class,
        'hr-HR' => Croatian::class,
        'fr' => French::class,
        'tl' => French::class,
        'pt-br' => French::class,
        'ru' => Russian::class,
        'ru-RU' => Russian::class,
        'lt' => Lithuanian::class,
        'cs' => Czech::class,
        'cs-CZ' => Czech::class,
        'sk' => Czech::class,
        'pl' => Polish::class,
        'is' => Icelandic::class,
        'sl-SL' => Slovenian::class,
    ];

    /**
     * Make a new pluralization rule.
     *
     * @param string $locale
     * @param array  $customPluralRules
     *
     * @return RuleInterface
     */
    public static function make(string $locale, array $customPluralRules = []): RuleInterface
    {
        if (isset($customPluralRules[$locale])) {
            if (!is_object($customPluralRules[$locale]) || !$customPluralRules[$locale] instanceof RuleInterface) {
                throw new RuntimeException('All custom plural rules must be classes '
                    . 'which implement the interface Polyglot\Pluralization\Rules\RuleInterface');
            }

            return $customPluralRules[$locale];
        }

        $rule = self::$localesRulesMapping[$locale] ?? German::class;

        return new $rule();
    }
}