<?php

namespace Buoy\Command;

use Buoy\Model\File;
use Buoy\Service\ConfigService;
use Buoy\Service\ScriptService;
use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetFileGroupCommand extends Command
{
    private const NAME = 'files:get-group';

    /** @var ScriptService */
    private $fileService;

    /** @var ConfigService */
    private $configService;

    /** @var OutputInterface */
    private $output;

    /** @var Client */
    private $client;

    /**
     * GetFileGroupCommand constructor.
     *
     * @param ScriptService $scriptService
     * @param ConfigService $configService
     */
    public function __construct(ScriptService $scriptService, ConfigService $configService)
    {
        parent::__construct(self::NAME);

        $this->addArgument('group', InputArgument::OPTIONAL, 'The group name', 'default');

        $this->configService = $configService;
        $this->client = new Client();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $group = $input->getArgument('group');
        $this->output = $output;

        if ($group === null) {
            $output->writeln('No group passed, if you want to run the default, pass default');
            return;
        }

        $files = $this->configService->getFilesForGroup($group);

        $this->fetchFiles($files);

        $output->writeln('Finished getting files');
    }

    /**
     * @param array|File[] $files
     *
     * @throws \Exception
     */
    private function fetchFiles(array $files): void
    {
        /** @var File $file */
        foreach ($files as $file) {
            $this->output->writeln('Fetching file ' . $file->getName());

            $temporaryFile = tempnam('', Uuid::uuid4());

            $handle = fopen($temporaryFile, 'wb');
            $this->client->get($file->getUrl(), ['headers' => $file->getHeaders(), 'sink' => $handle]);

            if (is_resource($handle)) {
                fclose($handle);
            }

            if (!$file->isUnzip()) {
                $success = @rename($temporaryFile, $file->getTarget());

                if (!$success) {
                    $this->output->writeln(
                        '<error>'
                            . 'We could not move the downloaded file from the temporary folder to the provided location.'
                            . ' This could be because the given path has a folder that does not exist.'
                            . ' Tried to move to ' . $file->getTarget()
                            . '</error>'
                    );
                }

                return;
            }

            $archive = new \ZipArchive();

            $this->output->writeln(sprintf('Going to extract the downloaded file: %s.', $file->getName()));

            $isOpen = $archive->open($temporaryFile);

            if (!$isOpen) {
                $this->output->writeln(
                    sprintf('<error>Could not open a zip archive: %s, skipping</error>', $file->getName())
                );

                return;
            }

            $archive->extractTo($file->getTarget());

            $archive->close();
        }
    }
}
