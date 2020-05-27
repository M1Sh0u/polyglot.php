<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Polyglot\Polyglot;

class UnsetMethodTest extends TestCase
{
    /**
     * @dataProvider polyglotProvider
     */
    public function testUnsetKeyBasedOnString(Polyglot $polyglot)
    {
        $polyglot->replace(['test_key' => 'test_value']);

        $this->assertTrue($polyglot->has('test_key'));

        $polyglot->unset('test_key');

        $this->assertFalse($polyglot->has('test_key'));
    }

    /**
     * @dataProvider polyglotProvider
     */
    public function testUnsetKeyBasedOnArray(Polyglot $polyglot)
    {
        $polyglot->replace(['test_key' => 'test_value', 'foo' => 'bar']);

        $this->assertTrue($polyglot->has('test_key'));
        $this->assertTrue($polyglot->has('foo'));

        $polyglot->unset(['test_key' => 'test_value', 'foo' => 'bar']);

        $this->assertFalse($polyglot->has('test_key'));
        $this->assertFalse($polyglot->has('foo'));
    }

    /**
     * @dataProvider polyglotProvider
     */
    public function testUnsetRecursively(Polyglot $polyglot)
    {
        $polyglot->replace(['test_key' => ['foo' => 'bar']]);

        $this->assertTrue($polyglot->has('test_key.foo'));

        $polyglot->unset(['test_key' => ['foo' => 'bar']]);

        $this->assertFalse($polyglot->has('test_key.foo'));
    }

    public function polyglotProvider(): array
    {
        return [[new Polyglot()]];
    }
}