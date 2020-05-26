Polyglot.php
===========

Polyglot.php is a tiny I18n helper library written in PHP, which is based entirely on the Polyglot.js Airbnb's I18n javascript library. 

The reason behind the decision to replicate the Airbnb's javascript library was to have a small, but yet powerful library, for developers to use the same way of internationalizing their PHP back-ends as they do in the front-end apps. 

Polylglot doesn’t perform any translation; it simply gives you a way to manage translated phrases from your server-side PHP application.

## Installation

install with [composer](https://getcomposer.org):

    $ composer require m1sh0u/polyglot.php

### Running the tests

Clone the repo, run `composer.phar update --prefer-dist --dev`, and `composer test`.

## Usage

### Instantiation

First, create an instance of the `Polyglot` class, which you will use for translation.

```php
$polyglot = new Polyglot();
```

Polyglot is class-based so you can maintain different sets of phrases at the same time, possibly in different locales.

See [Options Overview](#options-overview) for information about the options array you can choose to pass to `new Polyglot`.

### Translation

Tell Polyglot what to say by simply giving it a phrases object,
where the key is the canonical name of the phrase and the value is
the already-translated string.

```php
$polyglot->extend([
  "hello" => "Hello"
]);

$polyglot->t("hello");
=> "Hello"
```

You can also pass a mapping at instantiation, using the key `phrases`:

```php
$polyglot = new Polyglot(['phrases' => ["hello" => "Hello"]]);
```

Polyglot doesn’t do the translation for you. It’s up to you to give it
the proper phrases for the user’s locale.

### Interpolation

`Polyglot->t()` also provides interpolation. Pass an array with key-value pairs of
interpolation arguments as the second parameter.

```php
$polyglot->extend([
  "hello_name" => "Hola, %{name}."
]);

$polyglot->t("hello_name", ["name" => "DeNiro"]);
=> "Hola, DeNiro."
```

Polyglot also supports nested phrase objects.

```php
$polyglot->extend([
  "nav" => [
    "hello" => "Hello",
    "hello_name" => "Hello, %{name}",
    "sidebar" => [
      "welcome" => "Welcome"
    ]
  ]
]);

$polyglot->t("nav.sidebar.welcome");
=> "Welcome"
```

The substitution variable syntax is customizable.

```php
$polyglot = new Polyglot({
  "phrases" => [
    "hello_name" => "Hola {{name}}"
  ],
  "interpolation" => ["prefix" => "{{", "suffix" => "}}"]
});

$polyglot->t("hello_name", {name: "DeNiro"});
=> "Hola, DeNiro."
```

### Pluralization

For pluralization to work properly, you need to tell Polyglot what the current locale is. You can use `$polyglot->locale("fr")` to set the locale to, for example, French. This method is also a getter:

```php
$polyglot->locale()
=> "fr"
```

You can also pass this in during instantiation.

```php
$polyglot = new Polyglot(["locale" => "fr"]);
```

Currently, the _only_ thing that Polyglot uses this locale setting for is pluralization.

Polyglot provides a very basic pattern for providing pluralization based on a single string that contains all plural forms for a given phrase. Because various languages have different nominal forms for zero, one, and multiple, and because the noun can be before or after the count, we have to be overly explicit about the possible phrases.

To get a pluralized phrase, still use `$polyglot->t()` but use a specially-formatted phrase string that separates the plural forms by the delimiter `||||`, or four vertical pipe characters.

For pluralizing "car" in English, Polyglot assumes you have a phrase of the form:

```php
$polyglot->extend([
  "num_cars" => "%{smart_count} car |||| %{smart_count} cars",
]);
```

In English (and German, Spanish, Italian, and a few others) there are only two plural forms: singular and not-singular.

Some languages get a bit more complicated. In Czech, there are three separate forms: 1, 2 through 4, and 5 and up. Russian is even more involved.

```php
$polyglot = new Polyglot(["locale" => "cs"]); // Czech
$polyglot->extend([
  "num_foxes" => "Mám %{smart_count} lišku |||| Mám %{smart_count} lišky |||| Mám %{smart_count} lišek"
])
```

`$polyglot->t()` will choose the appropriate phrase based on the provided `smart_count` option, whose value is a number.

```php
$polyglot->t("num_cars", ["smart_count" => 0]);
=> "0 cars"

$polyglot->t("num_cars", ["smart_count" => 1]);
=> "1 car"

$polyglot->t("num_cars", ["smart_count" => 2]);
=> "2 cars"
```

As a shortcut, you can also pass a number to the second parameter:

```php
$polyglot->t("num_cars", 2);
=> "2 cars"
```


## Public Instance Methods

### Polyglot->t($key, $interpolationOptions)

The most-used method. Provide a key, and `t()` will return the phrase.

```php
$polyglot->t("hello");
=> "Hello"
```

The phrase value is provided first by a call to `$polyglot->extend()` or `$polyglot->replace()`.

Pass in an object as the second argument to perform interpolation.

```php
$polyglot->t("hello_name", ["name" => "Spike"]);
=> "Hello, Spike"
```

Pass a number as the second argument as a shortcut to `smart_count`:

```php
// same as: $polyglot->t("car", ["smart_count" => 2]);
$polyglot->t("car", 2);
=> "2 cars"
```

If you like, you can provide a default value in case the phrase is missing.
Use the special option key "_" to specify a default.

```php
$polyglot->t("i_like_to_write_in_language", [
  "_" => "I like to write in %{language}.",
  "language" => "JavaScript"
]);
=> "I like to write in JavaScript."
```

### Polyglot->extend($phrases)

Use `extend` to tell Polyglot how to translate a given key.

```php
$polyglot->extend([
  "hello" => "Hello",
  "hello_name" => "Hello, %{name}"
]);
```

The key can be any string. Feel free to call `extend` multiple times; it will override any phrases with the same key, but leave existing phrases untouched.

### Polyglot->unset($keyOrArray)
Use `unset` to selectively remove keys from a polyglot instance.
`unset` accepts one argument: either a single string key, or an array whose keys are string keys, and whose values are ignored unless they are nested arrays (in the same format).

Example:
```php
$polyglot->unset('some_key');
$polyglot->unset([
  'hello' => 'Hello',
  'hello_name' => 'Hello, %{name}',
  'foo' => [
    'bar' => 'This phrase’s key is "foo.bar"'
  ]
]);
```

### Polyglot->locale(?$localeToSet)

Get or set the locale (also can be set using the [constructor option](#options-overview), which is used only for pluralization.
If a truthy value is provided, it will set the locale. Afterwards, it will return it.

### Polyglot->clear()

Clears all phrases. Useful for special cases, such as freeing up memory if you have lots of phrases but no longer need to perform any translation. Also used internally by `replace`.


### Polyglot->replace($phrases)

Completely replace the existing phrases with a new set of phrases.
Normally, just use `extend` to add more phrases, but under certain circumstances, you may want to make sure no old phrases are lying around.

### Polyglot->has($key)

Returns `true` if the key does exist in the provided phrases, otherwise it will return `false`.

### Polyglot->phrases()

Returns all the phrases.

### Polyglot->transformPhrase($phrase[, $substitutions[, $locale[, $tokenRegex]]])

Takes a phrase string and transforms it by choosing the correct plural form and interpolating it. This method is used internally by `t`.
The correct plural form is selected if `$substitutions['smart_count']` is set.
You can pass in a number instead of an array as `$substitutions` as a shortcut for `smart_count`.
You should pass in a third argument, the locale, to specify the correct plural type. It defaults to `'en'` which has 2 plural forms.
You should pass in a forth argument, to specify the interpolation token regex. It defaults to `/%{(.*?)}/`.

## Options Overview
`new Polyglot` accepts a number of options:

 - `phrases`: a key/value map of translated phrases.
 - `locale`: a string describing the locale (language and region) of the translation, to apply pluralization rules. see [Pluralization](#pluralization)
 - `allowMissing`: a boolean to control whether missing keys in a `t` call are allowed. If `false`, by default, a missing key is returned and a warning is issued.
 - `onMissingKey`: if `allowMissing` is `true`, and this option is a function, then it will be called instead of the default functionality. Arguments passed to it are `$key`, `$options`, and `$locale`. The return of this function will be used as a translation fallback when `$polyglot->t('missing.key')` is called (hint: return the key).
 - `interpolation`: an array to change the substitution syntax for interpolation by setting the `prefix` and `suffix` fields.


## Related projects

- [Polyglot.js](https://airbnb.io/polyglot.js/): Polyglot.js is a tiny I18n helper library written in JavaScript, made to work both in the browser and in CommonJS environments (Node). It provides a simple solution for interpolation and pluralization, based off of Airbnb’s experience adding I18n functionality to its Backbone.js and Node apps.
