<?php

namespace Buoy\Container;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class BuoyContainerBuilder
 *
 * @package Buoy\Container
 *
 * Constructs the Buoy's Dependency Injection Container for further use throughout the whole application.
 */
class BuoyContainerBuilder
{
    /**
     * @param string $appDirectory
     * @param string $yamlDIConfigLocation
     *
     * @return Container
     *
     * @throws \Exception
     */
    public function constructContainer(string $appDirectory, string $yamlDIConfigLocation): Container
    {
        $containerBuilder = new ContainerBuilder();

        $loader = new YamlFileLoader($containerBuilder, new FileLocator($appDirectory));

        $loader->load($yamlDIConfigLocation);

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
