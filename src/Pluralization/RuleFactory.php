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

/**
 * Rule factory which crafts a pluralization rule depending on the provided locale.
 *
 * @package Polyglot/Pluralization
 * @author  Mihai MATEI <mihai.matei@smartbridge.ch>
 */
class RuleFactory
{
    /**
     * Make a new pluralization rule.
     *
     * @param string $locale
     *
     * @return RuleInterface
     */
    public static function make(string $locale): RuleInterface
    {
        switch($locale) {
            case 'ar':
                return new Arabic();

            case 'bs-Latn-BA':
            case 'bs-Cyrl-BA':
            case 'srl-RS':
            case 'sr-RS':
                return new BosnianSerbian();

            case 'id':
            case 'id-ID':
            case 'ja':
            case 'ko':
            case 'ko-KR':
            case 'lo':
            case 'ms':
            case 'th':
            case 'th-TH':
            case 'zh':
                return new Chinese();

            case 'hr':
            case 'hr-HR':
                return new Croatian();

            case 'fr':
            case 'tl':
            case 'pt-br':
                return new French();

            case 'ru':
            case 'ru-RU':
                return new Russian();

            case 'lt':
                return new Lithuanian();

            case 'cs':
            case 'cs-CZ':
            case 'sk':
                return new Czech();

            case 'pl':
                return new Polish();

            case 'is':
                return new Icelandic();

            case 'sl-SL':
                return new Slovenian();

            // 'fa', 'da', 'de', 'en', 'es', 'fi', 'el', 'he', 'hi-IN', 'hu', 'hu-HU', 'it', 'nl', 'no', 'pt', 'sv', 'tr'
            default:
                return new German();
        }
    }
}