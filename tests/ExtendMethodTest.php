<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class ExtendMethodTest extends TestCase
{
    /**
     * @dataProvider polyglotProvider
     */
    public function testOverridingOldKeys(Polyglot $polyglot)
    {
        $polyglot->extend(['aKey' => 'First']);
        $polyglot->extend(['aKey' => 'Second']);

        $this->assertSame($polyglot->t('aKey'), 'Second');
    }

    /**
     * @dataProvider polyglotProvider
     */
    public function testDoesNotForgetOldKeys(Polyglot $polyglot)
    {
        $polyglot->extend(['aKey' => 'First', 'bKey' => 'Second 1']);
        $polyglot->extend(['bKey' => 'Second 2']);

        $this->assertSame($polyglot->t('aKey'), 'First');
        $this->assertSame($polyglot->t('bKey'), 'Second 2');
    }

    /**
     * @dataProvider polyglotProvider
     */
    public function testSupportsOptionalPrefixArgument(Polyglot $polyglot)
    {
        $polyglot->extend(['click' => 'Click', 'hover' => 'Hover'], 'sidebar');

        $this->assertSame($polyglot->t('sidebar.click'), 'Click');
        $this->assertSame($polyglot->t('sidebar.hover'), 'Hover');

        $this->assertNotContains('click', $polyglot->phrases());
    }

    /**
     * @dataProvider polyglotProvider
     */
    public function testSupportsNestedArray(Polyglot $polyglot)
    {
        $polyglot->extend(['sidebar' => ['click' => 'Click', 'hover' => 'Hover'], 'nav' => ['header' => ['log_in' => 'Log In']]]);

        $phrases = $polyglot->phrases();

        $this->assertSame($phrases['sidebar.click'], 'Click');
        $this->assertSame($phrases['sidebar.hover'], 'Hover');
        $this->assertSame($phrases['nav.header.log_in'], 'Log In');

        $this->assertNotContains('click', $phrases);
        $this->assertNotContains('header.log_in', $phrases);
        $this->assertNotContains('log_in', $phrases);
    }

    public function polyglotProvider(): array
    {
        return [[new Polyglot()]];
    }
}