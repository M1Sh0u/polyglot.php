<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class ReplaceMethodTest extends TestCase
{
    public function testWippingOutOldPhrasesAndReplaceWithNewOnes()
    {
        $polyglot = new Polyglot(['phrases' => ['hiFriend' => 'Hi, Friend.', 'byeFriend' => 'Bye, Friend.']]);
        $polyglot->replace(['hiFriend' => 'Hi, Friend.']);

        $this->assertSame($polyglot->t('hiFriend'), 'Hi, Friend.');
        $this->assertNotContains('byeFriend', $polyglot->phrases());
    }
}