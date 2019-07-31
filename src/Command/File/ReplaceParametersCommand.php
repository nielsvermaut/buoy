<?php

namespace Buoy\Command\File;

use Buoy\Model\Script;
use Buoy\Service\ConfigService;
use Buoy\Service\ParameterService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReplaceParametersCommand extends Command
{
    private const NAME = 'file:replace';

    /** @var ParameterService */
    private $parameterService;

    /** @var OutputInterface */
    private $output;

    public function __construct(ParameterService $parameterService)
    {
        parent::__construct(self::NAME);

        $this->addArgument('files', InputArgument::IS_ARRAY);

        $this->addOption(
            'parameter',
            'p',
            InputOption::VALUE_REQUIRED,
            'The parameter to be replaced. Should be in a {% PARAMETER %} format in the file, but '
                . '{%PARAMETER%} is also allowed. As this option only the PARAMETER is allowed.'
        );

        $this->addOption(
            'value',
            null,
            InputOption::VALUE_REQUIRED,
            'The value to put in place of the {% PARAMETER %}'
        );

        $this->parameterService = $parameterService;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $filePaths = $input->getArgument('files');
        $parameter = $input->getOption('parameter');
        $value = $input->getOption('value');

        $failedCount = 0;

        foreach ($filePaths as $filePath) {
            try {
                $this->parameterService->replaceParameterInFile($filePath, $parameter, $value);
            } catch (\InvalidArgumentException $e) {
                $failedCount = $failedCount + 1;
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            }
        }

        $output->writeln(
            sprintf(
                'Finished replacing the parameters in %d files, which had %d failures',
                count($filePaths),
                $failedCount
            )
        );
    }
}
