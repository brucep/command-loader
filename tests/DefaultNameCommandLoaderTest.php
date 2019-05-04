<?php

namespace Brucep\CommandLoader\Tests;

use Brucep\CommandLoader\DefaultNameCommandLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;

class DefaultNameCommandLoaderTest extends TestCase
{
    public function testLoadsValidCommand(): void
    {
        $commandLoader = new DefaultNameCommandLoader([
            DefaultNameCommandLoaderTest_Example::class,
        ]);

        $this->assertTrue($commandLoader->has('example'));

        $this->assertInstanceOf(
            DefaultNameCommandLoaderTest_Example::class,
            $commandLoader->get('example')
        );
    }

    public function testExceptionOnNonexistentClass(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageRegExp('/Class does not exist: /');

        $commandLoader = new DefaultNameCommandLoader([
            'Brucep\\NonexistentClass',
        ]);
    }

    public function testExceptionOnInvalidClass(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageRegExp('/ subclass of /');

        $commandLoader = new DefaultNameCommandLoader([
            __CLASS__,
        ]);
    }

    public function testExceptionOnNullName(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageRegExp('/^Null /');

        $commandLoader = new DefaultNameCommandLoader([
            DefaultNameCommandLoaderTest_Null::class,
        ]);
    }

    public function testExceptionOnDuplicateName(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageRegExp('/ defined by /');

        $commandLoader = new DefaultNameCommandLoader([
            DefaultNameCommandLoaderTest_Example::class,
            DefaultNameCommandLoaderTest_Duplicate::class,
        ]);
    }
}

class DefaultNameCommandLoaderTest_Example extends Command
{
    protected static $defaultName = 'example';
}

class DefaultNameCommandLoaderTest_Null extends Command
{
    protected static $defaultName;
}

class DefaultNameCommandLoaderTest_Duplicate extends Command
{
    protected static $defaultName = 'example';
}
