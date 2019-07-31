<?php

declare(strict_types=1);

namespace Buoy;

use Buoy\Command\DB\RemoveCommand;
use Buoy\Command\DB\SpinUpCommand;
use Buoy\Command\File\GetFileGroupCommand;
use Buoy\Command\InitConfigCommand;
use Buoy\Command\File\ReplaceParametersCommand;
use Buoy\Container\BuoyContainerBuilder;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel
{
    const NAME = 'Buoy';
    const VERSION = '1.0.0';

    /**
     * @var ContainerBuilder
     */
    private $container;

    /** @var Application|null  */
    private $application;

    /**
     * Kernel constructor.
     *
     * @param string $appDirectory
     *
     * @throws \Exception
     */
    public function __construct(string $appDirectory)
    {
        $this->container = (new BuoyContainerBuilder())->constructContainer(
            $appDirectory,
            $appDirectory . '/src/Resources/config/services.yml'
        );
    }

    /**
     * Returns the configured console application to the caller. Lazily-loads the application.
     *
     * @return Application|null$
     *
     * @throws \Exception
     */
    public function getApplication()
    {
        if ($this->application !== null) {
            return $this->application;
        }

        $this->application = new Application(self::NAME, self::VERSION);

        foreach ($this->registeredCommands() as $command) {
            if ($command instanceof ContainerAwareInterface) {
                $command->setContainer($this->container);
            }

            $this->application->add($command);
        }

        return $this->application;
    }

    /**
     * Lists all the commands that are configured to be run in the application.
     *
     * @return array
     *
     * @throws \Exception
     */
    private function registeredCommands(): array
    {
        return [
            $this->container->get(SpinUpCommand::class),
            $this->container->get(RemoveCommand::class),
            $this->container->get(InitConfigCommand::class),
            $this->container->get(ReplaceParametersCommand::class),
            $this->container->get(GetFileGroupCommand::class),
        ];
    }
}