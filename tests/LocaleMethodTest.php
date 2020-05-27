<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class LocaleMethodTest extends TestCase
{
    public function testDefaultsToEnglish()
    {
        $this->assertSame((new Polyglot())->locale(), 'en');
    }

    public function testGetAndSetLocale()
    {
        $polyglot = new Polyglot(['locale' => 'fr']);

        $this->assertSame($polyglot->locale(), 'fr');

        $polyglot->locale('es');
        $this->assertSame($polyglot->locale(), 'es');

        $polyglot->locale('en');
        $this->assertSame($polyglot->locale(), 'en');

        $polyglot->locale('ro');
        $this->assertSame($polyglot->locale(), 'ro');
    }
}