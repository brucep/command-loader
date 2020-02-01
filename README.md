Add lazily loaded [symfony/console](https://github.com/symfony/console) commands by default name.

```php
use App\Command\HeavyCommand;
use App\Command\AnotherCommand;
use Brucep\CommandLoader\DefaultNameCommandLoader;
use Symfony\Component\Console\Application;

// Classes must define their $defaultName static property
$commandLoader = new DefaultNameCommandLoader([
    HeavyCommand::class,
    AnotherCommand::class,
]);

$application = new Application();
$application->setCommandLoader($commandLoader);
$application->run();
```

See also: [How to Make Commands Lazily Loaded](https://symfony.com/doc/4.4/console/lazy_commands.html)
