<?php

namespace Buoy\Command\DB;

use Buoy\Enum\Hooks;
use Buoy\Factory\ConnectionFactory;
use Buoy\Model\Script;
use Buoy\Model\Webhook;
use Buoy\Service\ConfigService;
use Buoy\Service\DatabaseService;
use Buoy\Service\ScriptService;
use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ConnectionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SpinUpCommand extends Command
{
    private const NAME = 'db:spin-up';

    /** @var DatabaseService */
    private $databaseService;

    /** @var ConfigService */
    private $configService;

    /** @var ScriptService */
    private $scriptService;

    /** @var ConnectionFactory */
    private $connectionFactory;

    public function __construct(
        DatabaseService $databaseService,
        ConfigService $configService,
        ScriptService $scriptService,
        ConnectionFactory $connectionFactory
    ) {
        parent::__construct(self::NAME);

        $this->databaseService = $databaseService;
        $this->configService = $configService;
        $this->scriptService = $scriptService;
        $this->connectionFactory = $connectionFactory;
    }

    public function configure()
    {
        $this->addArgument('serverUrl', InputArgument::REQUIRED, 'The server URL buoy is going to connect to');

        $this->addOption(
            'ownerUsername',
            null,
            InputOption::VALUE_OPTIONAL,
            'Defines a different owner than passed in the serverUrl'
        );

        $this->addUsage(
            'Will create a database in the supplied database url. It makes sure no collisions happen with the database '
                . 'names by generating new ones, and tracking their usage in a special table. Make sure that in the '
                . 'supplied server URL you pass user credentials with the power to remove and add database '
                . 'credentials. By default, the database will be owned the same user passed in the server URL, but '
                . 'optionally, you can pass --ownerUsername for the database to be granted to this user.'
        );
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
        $owner = $input->getOption('ownerUsername');

        $connection = $this->connectionFactory->create($serverUrl);

        try {
            $this->waitForConnection($connection);
        } catch (\Exception $e) {
            $output->writeln('<error>Could not connect to database server, halting!</error>');

            return 1;
        }

        $owner = $owner === null ? $connection->getUsername() : $owner;

        $name = (new \Nubs\RandomNameGenerator\Alliteration())->getName();
        $slug = (new Slugify())->slugify($name, ['separator' => '_']);

        $this->databaseService->setConnection($connection);
        $this->databaseService->createDatabase($slug);
        $this->databaseService->grantDatabaseToOwner($slug, $owner);

        $output->writeln($slug);

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