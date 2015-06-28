<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Command;

use LonelyPullRequests\Domain\Service\SyncService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pullrequests:sync')
            ->setDescription('Sync all pullrequests')

            ->addOption(
                'commit',
                null,
                InputOption::VALUE_NONE,
                'If set, the notifications will be cleared at source'
            )
            ->addOption(
                'all',
                null,
                InputOption::VALUE_NONE,
                'If set, all notifications, including read will be returned'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commit = (bool) $input->getOption('commit');
        $all = (bool) $input->getOption('all');

        /** @var SyncService $syncService */
        $syncService = $this->getContainer()->get('lonely_pull_requests.service.sync');
        $syncService->sync($commit, $all);
    }
}
