<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class ClearMethodTest extends TestCase
{
    public function testClear()
    {
        $polyglot = new Polyglot(['phrases' => ['hiFriend' => 'Hi, Friend.']]);
        $this->assertSame($polyglot->t('hiFriend'), 'Hi, Friend.');
        $polyglot->clear();
        $this->assertSame($polyglot->t('hiFriend'), 'hiFriend');

    }
}