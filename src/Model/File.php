<?php

namespace Buoy\Model;

class File
{
    /** @var string */
    private $name;

    /** @var string */
    private $url;

    /** @var array|string[] */
    private $headers;

    /** @var string */
    private $target;

    /** @var bool */
    private $unzip;

    /** @var string */
    private $group;

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
     * @return File
     */
    public function setName(string $name): File
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
     * @return File
     */
    public function setUrl(string $url): File
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array|string[] $headers
     *
     * @return File
     */
    public function setHeaders($headers): File
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     *
     * @return File
     */
    public function setTarget(string $target): File
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUnzip(): bool
    {
        return $this->unzip;
    }

    /**
     * @param bool $unzip
     *
     * @return File
     */
    public function setUnzip(bool $unzip): File
    {
        $this->unzip = $unzip;

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
     * @return File
     */
    public function setGroup(string $group): File
    {
        $this->group = $group;

        return $this;
    }
}
