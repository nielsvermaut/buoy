<?php

namespace Buoy\Model;

class Script
{
    /** @var array|string[] */
    private $runsOn;

    /** @var string */
    private $name;

    /** @var string */
    private $container;

    private $command;

    /** @var array|string[] */
    private $volumes;

    /** @var string */
    private $envFile = 'buoy.env';

    /** @var string */
    private $group = 'default';

    /** @var int */
    private $order = 999;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Script
     */
    public function setName(string $name): Script
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getContainer(): string
    {
        return $this->container;
    }

    /**
     * @param string $container
     * @return Script
     */
    public function setContainer(string $container): Script
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCommand(): ?string
    {
        return $this->command;
    }

    /**
     * @param string|null $command
     *
     * @return Script
     */
    public function setCommand(?string $command): Script
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getVolumes(): array
    {
        return $this->volumes;
    }

    /**
     * @param array|string[] $volumes
     *
     * @return Script
     */
    public function setVolumes(array $volumes): Script
    {
        $this->volumes = $volumes;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvFile(): string
    {
        return $this->envFile;
    }

    /**
     * @param string $envFile
     *
     * @return Script
     */
    public function setEnvFile(string $envFile): Script
    {
        $this->envFile = $envFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param string $group
     *
     * @return Script
     */
    public function setGroup(string $group): Script
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getRunsOn(): array
    {
        return $this->runsOn;
    }

    /**
     * @param array|string[] $runsOn
     *
     * @return Script
     */
    public function setRunsOn(array $runsOn): Script
    {
        $this->runsOn = $runsOn;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     *
     * @return Script
     */
    public function setOrder(int $order): Script
    {
        $this->order = $order;

        return $this;
    }
}
