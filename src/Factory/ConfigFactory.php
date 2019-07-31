<?php

namespace Buoy\Factory;

use Buoy\Model\Config;
use Buoy\Model\File;
use Buoy\Model\Script;
use Buoy\Model\Webhook;

/**
 * Class ConfigFactory
 *
 * @package Buoy\Factory
 */
class ConfigFactory
{
    public function createFromFileContents(array $contents): Config
    {
        $contents = $contents['buoy'];

        $config = new Config();

        $config
            ->setVersion($contents['version'])
            ->setScripts($this->createScripts($contents))
            ->setWebhooks($this->createWebhooks($contents))
            ->setFiles($this->createFiles($contents))
        ;

        return $config;
    }

    private function createScripts(array $contents): array
    {
        $scripts = [];

        foreach ($contents['scripts']  ?? [] as $scriptName => $scriptContent) {
            $scripts[] = (new Script())
                ->setName($scriptName)
                ->setContainer($scriptContent['docker_container'])
                ->setRunsOn($scriptContent['runs_on'] ?? [])
                ->setCommand($scriptContent['command'] ?? null)
                ->setEnvFile($scriptContent['env_file'] ?? 'buoy.yml')
                ->setGroup($scriptContent['group'] ?? 'default')
                ->setOrder($scriptContent['order'] ?? 999)
            ;
        }

        uasort($scripts, static function (Script $current, Script $next) {
            return $current->getOrder() <=> $next->getOrder();
        });

        return $scripts;
    }

    private function createWebhooks(array $contents): array
    {
        $webhooks = [];

        foreach ($contents['webhooks'] ?? [] as $webhookName => $webhookContent) {
            $webhooks[] = (new Webhook())
                ->setName($webhookName)
                ->setRunsOn($webhookContent['runs_on'] ?? [])
                ->setOrder($webhookContent['order'] ?? 999)
                ->setPayload($webhookContent['payload'] ?? [])
                ->setHeaders($webhookContent['headers'] ?? [])
                ->setIncludeContext($webhookContent['include_context'] ?? false)
            ;
        }

        uasort($webhooks, static function (Webhook $current, Webhook $next) {
            return $current->getOrder() <=> $next->getOrder();
        });

        return $webhooks;
    }

    private function createFiles(array $contents): array
    {
        $files = [];

        foreach ($contents['files'] ?? [] as $fileName => $fileContent) {
            $files[] = (new File())
                ->setName($fileName)
                ->setGroup($fileContent['group'] ?? 'default')
                ->setHeaders($fileContent['headers'] ?? [])
                ->setTarget($fileContent['target'])
                ->setUnzip($fileContent['unzip'] ?? false)
                ->setUrl($fileContent['url'])
            ;
        }

        return $files;
    }
}
