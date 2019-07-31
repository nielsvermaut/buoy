<?php

namespace Buoy\Command\DB;

use Buoy\Factory\ConnectionFactory;
use Buoy\Service\DatabaseService;
use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ConnectionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends Command
{
    private const NAME = 'db:remove';

    /** @var DatabaseService */
    private $databaseService;

    /** @var ConnectionFactory */
    private $connectionFactory;

    public function __construct(
        DatabaseService $databaseService,
        ConnectionFactory $connectionFactory
    ) {
        parent::__construct(self::NAME);

        $this->databaseService = $databaseService;
        $this->connectionFactory = $connectionFactory;
    }

    public function configure()
    {
        $this->addArgument('serverUrl', InputArgument::REQUIRED, 'The server URL buoy is going to connect to');

        $this->addArgument(
            'databaseName',
            null,
            InputArgument::REQUIRED,
            'Defines a different owner than passed in the serverUrl'
        );

        $this->addUsage('Will remove the supplied database in the supplied database url.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $serverUrl = $input->getArgument('serverUrl');
        $databaseName = $input->getArgument('databaseName');

        $connection = $this->connectionFactory->create($serverUrl);

        try {
            $this->waitForConnection($connection);
        } catch (\Exception $e) {
            $output->writeln('<error>Could not connect to database server, halting!</error>');

            return 1;
        }

        $this->databaseService->setConnection($connection);
        $this->databaseService->deleteDatabase($databaseName);

        $output->writeln('Successfully removed ' . $databaseName);

        return 0;
    }

    /**
     * @param Connection $connection
     *
     * @throws \DomainException
     */
    private function waitForConnection(Connection $connection): void
    {
        $tries = 0;

        do {
            try {
                $connection->connect();
                return;
            } catch (ConnectionException $e) {
                ++$tries;
                sleep(15);
            }
        } while ($tries <= 5);

        throw new \DomainException('Failed to connect to MySQL Server.');
    }
}