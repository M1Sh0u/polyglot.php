<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class PluralizationTest extends TestCase
{
    private $phrases = [
        'count_name' => '%{smart_count} Name |||| %{smart_count} Names',
    ];

    /**
     * @var Polyglot
     */
    private $polyglot;

    public function setUp(): void
    {
        $this->polyglot = new Polyglot(['phrases' => $this->phrases, 'locale' => 'en']);
    }

    public function testPluralizationWithAnInteger(): void
    {
        $this->assertSame(
            $this->polyglot->t('count_name', ['smart_count' => 0]),
            '0 Names'
        );
        $this->assertSame(
            $this->polyglot->t('count_name', ['smart_count' => 1]),
            '1 Name'
        );
        $this->assertSame(
            $this->polyglot->t('count_name', ['smart_count' => 2]),
            '2 Names'
        );
        $this->assertSame(
            $this->polyglot->t('count_name', ['smart_count' => 3]),
            '3 Names'
        );
    }

    public function testAcceptsNumberAsAShortcutToPluralizeWord(): void
    {
        $this->assertSame(
            $this->polyglot->t('count_name', 0),
            '0 Names'
        );
        $this->assertSame(
            $this->polyglot->t('count_name', 1),
            '1 Name'
        );
        $this->assertSame(
            $this->polyglot->t('count_name', 2),
            '2 Names'
        );
        $this->assertSame(
            $this->polyglot->t('count_name', 3),
            '3 Names'
        );
    }

    public function testIgnoresARegionSubtagWhenChoosingAPluralizationRule(): void
    {
        $polyglot = new Polyglot(['phrases' => $this->phrases, 'locale' => 'fr-FR']);

        $this->assertSame(
            $polyglot->t('count_name', 3),
            '3 Names'
        );
    }
}