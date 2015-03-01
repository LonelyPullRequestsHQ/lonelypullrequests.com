<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Command;

use LonelyPullRequests\Domain\Loneliness;
use LonelyPullRequests\Domain\Notification;
use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\Repository\NotificationRepository;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use LonelyPullRequests\Infrastructure\Service\PullRequestSyncService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commit = (bool) $input->getOption('commit');

        /** @var ContainerInterface $container */
        $container = $this->getContainer();

        /** @var PullRequestsRepository $pullRequestRepository */
        $pullRequestRepository = $container->get('lonely_pull_requests.repository.pull_requests');

        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = $container->get('lonely_pull_requests.repository.notification');

        $syncService = new PullRequestSyncService($pullRequestRepository, $notificationRepository);
        $syncService->sync($commit);
    }
}
