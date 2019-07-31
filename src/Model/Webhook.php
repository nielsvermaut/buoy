<?php

namespace Buoy\Model;

class Webhook
{
    /** @var string */
    private $name;

    /** @var string */
    private $url;

    /** @var array|null */
    private $payload = null;

    /** @var string */
    private $method;

    /** @var array */
    private $headers;

    /** @var string|null */
    private $group;

    /** @var int */
    private $order = 999;

    /** @var array|string[] */
    private $runsOn;

    /** @var bool */
    private $includeContext = false;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Webhook
     */
    public function setName(string $name): Webhook
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Webhook
     */
    public function setUrl(string $url): Webhook
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function getPayload(): ?array
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     *
     * @return Webhook
     */
    public function setPayload(array $payload): Webhook
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return Webhook
     */
    public function setMethod(string $method): Webhook
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return Webhook
     */
    public function setHeaders(array $headers): Webhook
    {
        $this->headers = $headers;

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
     * @return Webhook
     */
    public function setOrder(int $order): Webhook
    {
        $this->order = $order;

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
     * @return Webhook
     */
    public function setRunsOn($runsOn): Webhook
    {
        $this->runsOn = $runsOn;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIncludeContext(): bool
    {
        return $this->includeContext;
    }

    /**
     * @param bool $includeContext
     *
     * @return Webhook
     */
    public function setIncludeContext(bool $includeContext): Webhook
    {
        $this->includeContext = $includeContext;

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
     * @return Webhook
     */
    public function setGroup(string $group): Webhook
    {
        $this->group = $group;

        return $this;
    }
}
