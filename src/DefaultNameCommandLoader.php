<?php

namespace Brucep\CommandLoader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

final class DefaultNameCommandLoader implements CommandLoaderInterface
{
    private $commands;

    public function __construct(array $classes)
    {
        foreach ($classes as $class) {
            if (!class_exists($class)) {
                throw new \LogicException(sprintf(
                    'Class does not exist: %s',
                    $class
                ));
            }

            if (!is_subclass_of($class, Command::class, true)) {
                throw new \LogicException(sprintf(
                    'Class %s is not a subclass of %s.',
                    $class,
                    Command::class
                ));
            }

            $name = call_user_func($class.'::getDefaultName');

            if (null === $name) {
                throw new \LogicException('Null is not a supported command name.');
            }

            if (isset($this->commands[$name])) {
                throw new \LogicException(sprintf(
                    'Key "%s" is defined by %s and %s.',
                    $name,
                    $this->commands[$name],
                    $class
                ));
            }

            $this->commands[$name] = $class;
        }
    }

    public function has($name)
    {
        return isset($this->commands[$name]);
    }

    public function get($name)
    {
        if (!isset($this->commands[$name])) {
            throw new CommandNotFoundException(sprintf(
                'Command "%s" does not exist.',
                $name
            ));
        }

        $command = $this->commands[$name];

        return new $command();
    }

    public function getNames()
    {
        return array_keys($this->commands);
    }
}
