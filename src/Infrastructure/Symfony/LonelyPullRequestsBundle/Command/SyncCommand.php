<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Command;

use LonelyPullRequests\Domain\Loneliness;
use LonelyPullRequests\Domain\Notification;
use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\Repository\NotificationRepository;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SyncCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pullrequests:sync')
            ->setDescription('Sync all pullrequests')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commit = false;

        // Figure this out for new ones
        $loneliness = Loneliness::fromInteger(0);

        /** @var ContainerInterface $container */
        $container = $this->getContainer();

        /** @var PullRequestsRepository $pullRequestRepository */
        $pullRequestRepository = $container->get('lonely_pull_requests.repository.pull_requests');

        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = $container->get('lonely_pull_requests.repository.notification');

        $lastEventDateTime = null;

        // Parse notifications for new pull requests
        $notifications = $notificationRepository->all();
        foreach($notifications as $notification) {
            $output->writeln("Parsing notification for URL: " . $notification->url()->toString());
            /** @var Notification $notification */

            $pullRequest = $pullRequestRepository->getByRepositoryName($notification->repositoryName());
            if($pullRequest === null) {
                $pullRequest = PullRequest::create($notification->title(), $notification->repositoryName(), $notification->url(), $loneliness);
                $output->writeln("\tCreating PullRequest object for URL: " . $notification->url()->toString());
            }

            $pullRequestRepository->add($pullRequest);

            // Update latest entry
            if($lastEventDateTime === null || $notification->eventDateTime() > $lastEventDateTime) {
                $lastEventDateTime = $notification->eventDateTime();
            }
        }

        if($commit && $lastEventDateTime !== null) {
            $notificationRepository->markRead($lastEventDateTime);
        }

        $output->writeln('');
        $output->writeln("Mayday! Mayday! We are syncing! We are syncing!");
        $output->writeln('');
    }
}
