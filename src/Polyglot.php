<?php

declare(strict_types=1);

namespace MihaiMATEI\Polyglot;

use MihaiMATEI\Polyglot\Pluralization\RuleFactory;
use RuntimeException;

/**
 * Polyglot class based on Airbnb's Polyglot.js tiny helper.
 *
 * @package Polyglot
 * @author  Mihai MATEI <mihai.matei@outlook.com>
 */
class Polyglot
{
    /**
     * @var array
     */
    private $phrases = [];

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var string
     */
    private $currentLocale;

    /**
     * @var null|callable
     */
    private $onMissingKey;

    /**
     * @var callable
     */
    private $warn;

    /**
     * @var string
     */
    private $tokenRegex;

    /**
     * Class constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->extend($options['phrases'] ?? []);

        $this->currentLocale = $options['locale'] ?? 'en';
        $this->delimiter = $options['delimiter'] ?? '||||';
        $this->tokenRegex = $this->constructTokenRegex($options['interpolation'] ?? []);
        $this->warn = $options['warn'] ?? static function(string $message) {};

        $allowMissing = ($options['allowMissing'] ?? false) === true
            ? function (...$params) {
                return $this->transformPhrase(...$params);
              }
            : null;
        $this->onMissingKey = is_callable($options['onMissingKey'] ?? false) ? $options['onMissingKey'] : $allowMissing;
    }

    /**
     * $polyglot->extend($phrases)
     *
     * Use `extend` to tell Polyglot how to translate a given key.
     *
     * $polyglot->extend([
     *     "hello" => "Hello",
     *     "hello_name" => "Hello, %{name}"
     * ]);
     *
     * The key can be any string. Feel free to call `extend` multiple times;
     * it will override any phrases with the same key, but leave existing phrases
     * untouched.
     *
     * It is also possible to pass nested phrase objects, which get flattened
     * into an object with the nested keys concatenated using dot notation.
     *
     * $polyglot->extend([
     *     "nav" => [
     *          "hello" => "Hello",
     *          "hello_name" => "Hello, %{name}",
     *          "sidebar" => [
     *              "welcome" => "Welcome",
     *          ]
     *      ]
     * ]);
     *
     * var_dump($polyglot->phrases());
     *
     *   // [
     *   //   'nav.hello' => 'Hello',
     *   //   'nav.hello_name' => 'Hello, %{name}',
     *   //   'nav.sidebar.welcome' => 'Welcome'
     *   // ]
     *
     * `extend` accepts an optional second argument, `prefix`, which can be used
     * to prefix every key in the phrases object with some string, using dot
     * notation.
     *
     * $polyglot->extend([
     *     "hello" => "Hello",
     *     "hello_name" => "Hello, %{name}"
     * ], "nav");
     *
     * var_dump($polyglot->phrases());
     *
     *   // [
     *   //   'nav.hello' => 'Hello',
     *   //   'nav.hello_name' => 'Hello, %{name}'
     *   // ]
     *
     * @param array       $morePhrases
     * @param string|null $prefix
     */
    public function extend(array $morePhrases = [], ?string $prefix = null): void
    {
        foreach ($morePhrases as $key => $phrase) {
            $prefixedKey = $prefix !== null ? $prefix . '.' . $key : $key;

            if (is_array($phrase)) {
                $this->extend($phrase, $prefixedKey);
            } else {
                $this->phrases[$prefixedKey] = $phrase;
            }
        }
    }

    /**
     * $polyglot->unset($phrases)
     *
     * Use `unset` to selectively remove keys from a polyglot instance.
     *
     * $polyglot->unset("some_key");
     * $polyglot->unset([
     *     "hello" => "Hello",
     *     "hello_name" => "Hello, %{name}"
     * ]);
     *
     * The unset method can take either a string (for the key), or an array with
     * the keys that you would like to unset.
     *
     * @param string|array $morePhrases
     * @param string|null  $prefix
     */
    public function unset($morePhrases, ?string $prefix = null): void
    {
        if (is_string($morePhrases)) {
            unset($this->phrases[$morePhrases]);
        } else {
            foreach ($morePhrases as $key => $phrase) {
                $prefixedKey = $prefix !== null ? $prefix . '.' . $key : $key;

                if (is_array($phrase)) {
                    $this->unset($phrase, $prefixedKey);
                } else {
                    unset($this->phrases[$prefixedKey]);
                }
            }
        }

    }

    /**
     * $polyglot->clear()
     *
     * Clears all phrases. Useful for special cases, such as freeing
     * up memory if you have lots of phrases but no longer need to
     * perform any translation. Also used internally by `replace`.
     */
    public function clear(): void
    {
        $this->phrases = [];
    }

    /**
     * $polyglot->replace($phrases)
     *
     * Completely replace the existing phrases with a new set of phrases.
     * Normally, just use `extend` to add more phrases, but under certain
     * circumstances, you may want to make sure no old phrases are lying around.
     *
     * @param array       $newPhrases
     * @param string|null $prefix
     */
    public function replace(array $newPhrases, ?string $prefix = null): void
    {
        $this->clear();
        $this->extend($newPhrases, $prefix);
    }

    /**
     * $polyglot->locale(?$locale)
     *
     * Get or set locale. Internally, Polyglot only uses locale for pluralization.
     *
     * @param string|null $locale
     *
     * @return string
     */
    public function locale(?string $locale = null): string
    {
        if ($locale !== null) {
            $this->currentLocale = $locale;
        }

        return $locale;
    }

    /**
     * $polyglot->has($key)
     *
     * Check if polyglot has a translation for given key
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->phrases);
    }

    public function phrases(): array
    {
        return $this->phrases;
    }

    /**
     * $polyglot->t($key, $options)
     *
     * The most-used method. Provide a key, and `t` will return the phrase.
     *
     * $polyglot->t("hello");
     * => "Hello"
     *
     * The phrase value is provided first by a call to `$polyglot->extend($phrases)` or `$polyglot->replace($phrases)`.
     *
     * Pass in an object as the second argument to perform interpolation.
     *
     * $polyglot->t("hello_name", ["name" => "Spike"]);
     * => "Hello, Spike"
     *
     * If you like, you can provide a default value in case the phrase is missing.
     * Use the special option key "_" to specify a default.
     *
     * $polyglot->t("i_like_to_write_in_language", [
     *     '_' => "I like to write in %{language}.",
     *     'language' => "JavaScript"
     * ]);
     * => "I like to write in JavaScript."
     *
     * @param string $key
     * @param array  $options
     *
     * @return string
     */
    public function t(string $key, array $options = []): string
    {
        $phrase = '';
        $result = '';

        if ($this->has($key) && is_string($this->phrases[$key])) {
            $phrase = $this->phrases[$key];
        } elseif(is_string($options['_'] ?? false)) {
            $phrase = $options['_'];
        } elseif ($this->onMissingKey !== null) {
            $result = call_user_func($this->onMissingKey, $key, $options, $this->currentLocale, $this->tokenRegex);
        } else {
            // warn missing translations
            call_user_func($this->warn, 'Missing translation for key: "' . $key . '"');

            $result = $key;
        }

        if (is_string($phrase)) {
            $result = $this->transformPhrase($phrase, $options, $this->currentLocale, $this->tokenRegex);
        }

        return $result;
    }

    /**
     * $polyglot->transformPhrase($phrase, $substitutions, $locale)
     *
     * Takes a phrase string and transforms it by choosing the correct plural form and interpolating it.
     *
     * $polyglot->transformPhrase('Hello, %{name}!', ['name' => 'Spike']);
     * => "Hello, Spike!"
     *
     * The correct plural form is selected if substitutions['smart_count'] is set. You can pass in a number instead
     * of an array as `$substitutions` as a shortcut for `smart_count`.
     *
     * $polyglot->transformPhrase('%{smart_count} new messages |||| 1 new message', ['smart_count' => 1], 'en');
     * => "1 new message"
     *
     * $polyglot->transformPhrase('%{smart_count} new messages |||| 1 new message', ['smart_count' => 2], 'en');
     * => "2 new messages"
     *
     * $polyglot->transformPhrase('%{smart_count} new messages |||| 1 new message', ['smart_count' => 5], 'en');
     * => "5 new messages"
     *
     * You should pass in a third argument, the locale, to specify the correct plural type.
     * It defaults to `'en'` with 2 plural forms.
     *
     * @param string      $phrase
     * @param null        $substitutions
     * @param string      $locale
     * @param string|null $tokenRegex
     *
     * @return string
     */
    public function transformPhrase(string $phrase, $substitutions = null, string $locale = 'en', ?string $tokenRegex = null): string
    {
        if (!is_string($phrase)) {
            throw new RuntimeException('Polyglot.transformPhrase expects argument #1 to be string');
        }

        if ($substitutions === null) {
            return $phrase;
        }

        $result = $phrase;

        // allow number as a pluralization shortcut
        $options = is_int($substitutions) ? ['smart_count' => $substitutions] : $substitutions;

        // Select plural form: based on a phrase text that contains `n`
        // plural forms separated by `delimiter`, a `locale`, and a `substitutions.smart_count`,
        // choose the correct plural form. This is only done if `count` is set.
        if (($options['smart_count'] ?? null) !== null && $result) {
            $texts = explode($this->delimiter, $result);
            $pluralTypeIndex = RuleFactory::make($locale)($options['smart_count']);
            $result = $texts[$pluralTypeIndex] ?? $texts[0];
        }

        $interpolationRegex = $tokenRegex ?? $this->tokenRegex;

        return preg_replace_callback($interpolationRegex, static function($matches) use ($options) {
            return $options[$matches[1]] ?? $matches[1];
        }, $result);
    }

    /**
     * Construct a new token regex.
     *
     * @param array $options
     *
     * @return string
     */
    private function constructTokenRegex(array $options = []): string
    {
        $prefix = $options['prefix'] ?? '%{';
        $suffix = $options['suffix'] ?? '}';

        if ($prefix === $this->delimiter || $suffix === $this->delimiter) {
            throw new RuntimeException('"' . $this->delimiter . '" token is reserved for pluralization');
        }

        return '~' . $prefix . '(.*?)' . $suffix . '~';
    }
}