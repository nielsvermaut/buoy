<?php

namespace Buoy\Service;

use Buoy\Enum\Hooks;
use Buoy\Factory\ConfigFactory;
use Buoy\Model\Config;
use Buoy\Model\File;
use Buoy\Model\Script;
use Buoy\Model\Webhook;
use Symfony\Component\Yaml\Yaml;

class ConfigService
{
    /** @var ConfigFactory */
    private $factory;

    /** @var Config|null */
    private $config;

    public function __construct(ConfigFactory $configFactory)
    {
        $this->factory = $configFactory;
    }

    public function isConfigPresent(): bool
    {
        return file_exists(getcwd() . '/buoy.yml');
    }

    /**
     * @param string $hook
     *
     * @return array|Script[]
     */
    public function getScriptsForHook(string $hook): array
    {
        if (!in_array($hook, Hooks::getSupportedHooks(), true)) {
            throw new \DomainException(
                'Given hook does not exist in supported hooks. If you are trying to group commands into logical '
                    . 'blocks, define them as a group instead'
            );
        }

        $scripts = array_filter($this->getConfig()->getScripts(), static function (Script $script) use ($hook) {
           return in_array($hook, $script->getRunsOn(), true);
        });

        uasort($scripts, static function (Script $current, Script $next) {
            return $current->getOrder() <=> $next->getOrder();
        });

        return array_values($scripts);
    }

    /**
     * @param string $hook
     *
     * @return array|Webhook[]
     */
    public function getWebhooksForHook(string $hook): array
    {
        if (!in_array($hook, Hooks::getSupportedHooks(), true)) {
            throw new \DomainException(
                'Given hook does not exist in supported hooks. If you are trying to group commands into logical '
                . 'blocks, define them as a group instead'
            );
        }

        $webhooks = array_filter($this->getConfig()->getWebhooks(), static function (Webhook $webhook) use ($hook) {
            return in_array($hook, $webhook->getRunsOn(), true);
        });

        uasort($webhooks, static function (Webhook $current, Webhook $next) {
            return $current->getOrder() <=> $next->getOrder();
        });

        return array_values($webhooks);
    }

    /**
     * @param string $group
     *
     * @return array
     */
    public function getScriptsForGroup(string $group = 'default'): array
    {
        $scripts = array_filter($this->getConfig()->getScripts(), static function (Script $script) use ($group) {
            return $script->getGroup() === $group;
        });

        uasort($scripts, static function (Script $current, Script $next) {
            return $current->getOrder() <=> $next->getOrder();
        });

        return array_values($scripts);
    }

    /**
     * @param string $group
     *
     * @return array
     */
    public function getFilesForGroup(string $group = 'default'): array
    {
        $files = array_filter($this->getConfig()->getFiles(), static function (File $file) use ($group) {
            return $file->getGroup() === $group;
        });

        return array_values($files);
    }

    /**
     * @return Config
     */
    private function getConfig(): Config
    {
        if ($this->config !== null) {
            return $this->config;
        }

        $contents = Yaml::parse(file_get_contents(getcwd() . '/buoy.yml'));

        $this->config = $this->factory->createFromFileContents($contents);

        return $this->config;
    }
}
