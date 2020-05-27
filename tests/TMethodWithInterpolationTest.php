<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class TMethodWithInterpolationTest extends TestCase
{
    public function testInterpolatesWithTheSpecifiedCustomTokenSyntax(): void
    {
        $polyglot = $this->makePolyglot(['prefix' => '{{', 'suffix' => '}}']);

        $this->assertSame($polyglot->t('Welcome {{name}}', ['name' => 'Mihai']), 'Welcome Mihai');
    }

    public function testInterpolatesWithSamePrefixAndSuffix(): void
    {
        $polyglot = $this->makePolyglot(['prefix' => '|', 'suffix' => '|']);

        $this->assertSame($polyglot->t('Welcome |name|, how are you, |name|?', ['name' => 'Mihai']), 'Welcome Mihai, how are you, Mihai?');
    }

    public function testInterpolatesWhenUsingRegularExpressionTokens(): void
    {
        $polyglot = $this->makePolyglot(['prefix' => '\\s.*', 'suffix' => '\\d.+']);

        $this->assertSame($polyglot->t('Welcome \\s.*name\\d.+', ['name' => 'Mihai']), 'Welcome Mihai');
    }

    public function testThrowsErrorIfPluralizationDelimiterIsUsedToPrefix(): void
    {
        $this->expectException(RuntimeException::class);

        $this->makePolyglot(['prefix' => '||||', 'suffix' => '}}']);
    }

    public function testThrowsErrorIfPluralizationDelimiterIsUsedToSuffix(): void
    {
        $this->expectException(RuntimeException::class);

        $this->makePolyglot(['prefix' => '}}', 'suffix' => '||||']);
    }

    public function makePolyglot(array $interpolation): Polyglot
    {
        return new Polyglot([
            'allowMissing' => true,
            'interpolation' => $interpolation
        ]);
    }
}