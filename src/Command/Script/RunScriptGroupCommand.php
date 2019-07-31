<?php

namespace Buoy\Command\Script;

use Buoy\Model\Script;
use Buoy\Service\ConfigService;
use Buoy\Service\ScriptService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunScriptGroupCommand extends Command
{
    private const NAME = 'scripts:run-group';

    /** @var ScriptService */
    private $scriptService;

    /** @var ConfigService */
    private $configService;

    /** @var OutputInterface */
    private $output;

    public function __construct(ScriptService $scriptService, ConfigService $configService)
    {
        parent::__construct(self::NAME);

        $this->addArgument('group', InputArgument::REQUIRED, 'The group name');

        $this->scriptService = $scriptService;
        $this->configService = $configService;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $group = $input->getArgument('group');
        $this->output = $output;

        if ($group === null) {
            $output->writeln('No group passed, if you want to run the default, pass default');
            return;
        }

        $scripts = $this->configService->getScriptsForGroup($group);

        $this->runScripts($scripts);

        $output->writeln('Finished running scripts');
    }

    /**
     * @param array|Script[] $scripts
     */
    private function runScripts(array $scripts): void
    {
        foreach ($scripts as $script) {
            $this->output->writeln('Running script ' . $script->getName());

            $this->scriptService->runScript($script);
        }
    }
}
