<?php

namespace Buoy\Command\Webhook;

use Buoy\Model\Script;
use Buoy\Service\ConfigService;
use Buoy\Service\ScriptService;
use Buoy\Service\WebhookService;
use Spatie\Emoji\Emoji;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunWebhookGroupCommand extends Command
{
    private const NAME = 'webhook:run-group';

    /** @var WebhookService */
    private $webhookService;

    /** @var ConfigService */
    private $configService;

    /** @var OutputInterface */
    private $output;

    public function __construct(ConfigService $configService, WebhookService $webhookService)
    {
        parent::__construct(self::NAME);

        $this->addArgument('group', InputArgument::REQUIRED, 'The group name');

        $this->webhookService = $webhookService;
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

        $webhooks = $this->configService->getWebhooksForGroup($group);

        $failureCount = 0;

        foreach ($webhooks as $webhook) {
            $section = $output->section();
            $section->writeln(sprintf('Sending webhook %s: %s', $webhook->getName(), Emoji::hourglassNotDone()));

            try {
                $this->webhookService->fireWebhook($webhook);
                $section->overwrite(sprintf('Sending webhook %s: %s', $webhook->getName(), Emoji::heavyCheckMark()));
            } catch (\Exception $e) {
                $failureCount = $failureCount + 1;
                $section->overwrite(sprintf('Sending webhook %s: %s', $webhook->getName(), Emoji::prohibited()));
                $output->writeln($e->getMessage());
            }
        }

        $output->writeln(sprintf('Finished running %d webhooks, having failed %d', count($webhooks), $failureCount));
    }
}
