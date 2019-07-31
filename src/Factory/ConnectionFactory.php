<?php

namespace Buoy\Factory;

use Buoy\Service\DatabaseService;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

/**
 * Class ConnectionFactory
 *
 * @package Buoy\Factory
 */
class ConnectionFactory
{
    /**
     * @param string $connectionUrl
     *
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    public function create(string $connectionUrl): Connection
    {
        $config = new Configuration();

        return DriverManager::getConnection(['url' => $connectionUrl, 'dbname' => 'information_schema'], $config);
    }
}
