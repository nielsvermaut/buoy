<?php

namespace Buoy\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitConfigCommand extends Command
{
    private const NAME = 'init';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $currentWorkingDirectory = getcwd();

        if (file_exists($currentWorkingDirectory . '/buoy.yml')) {
            $output->writeln(
                'This project already has buoy initialized. If you want to start over, remove the buoy.yml file in '
                    . 'your project root, and run buoy init again'
            );
        }

        $contents = file_get_contents(BUOY_PATH . '/src/Resources/template/buoy.yml.dist');

        file_put_contents($currentWorkingDirectory . '/buoy.yml', $contents);
        touch($currentWorkingDirectory . '/buoy.env');

        $output->writeln(
            'Successfully created the buoy.yml file in the current working directory, and added the buoy.env '
                . 'file as a bonus!'
        );
    }
}