<?php

namespace Buoy\Model;

class Config
{
    /** @var int */
    private $version;

    /** @var array|Script[] */
    private $scripts = [];

    /** @var array|Webhook[] */
    private $webhooks = [];

    /** @var array|File[] */
    private $files = [];

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     *
     * @return Config
     */
    public function setVersion(int $version): Config
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return array|Script[]
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    /**
     * @param array|Script[] $scripts
     *
     * @return Config
     */
    public function setScripts($scripts): Config
    {
        $this->scripts = $scripts;

        return $this;
    }

    /**
     * @return array|Webhook[]
     */
    public function getWebhooks(): array
    {
        return $this->webhooks;
    }

    /**
     * @param array|Webhook[] $webhooks
     *
     * @return Config
     */
    public function setWebhooks(array $webhooks): Config
    {
        $this->webhooks = $webhooks;

        return $this;
    }

    /**
     * @return array|File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array|File[] $files
     *
     * @return Config
     */
    public function setFiles($files): Config
    {
        $this->files = $files;

        return $this;
    }
}