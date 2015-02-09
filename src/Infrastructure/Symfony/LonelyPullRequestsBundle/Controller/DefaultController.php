<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Controller;

use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Infrastructure\Persistence\InMemoryPullRequestsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /** @var InMemoryPullRequestsRepository $repository */
        $repository = $this->get('lonely_pull_requests.repository.pull_requests');

        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getEntityManager();
        $pullRequestRepository = $manager->getRepository('LonelyPullRequests\Domain\PullRequest');
        echo '<pre>';

        $pr = PullRequest::fromArray(
            [
                'summary' => 'My great pull request',
                'repositoryName' => 'LonelyPullRequestsHQ/lonelypullrequests.com',
                'url' => 'https://github.com/LonelyPullRequestsHQ/lonelypullrequests.com/pull/7',
                'loneliness' => 50
            ]
        );

        //$all = $pullRequestRepository->add($pr);
        $all = $pullRequestRepository->findAll();

        print_r($all);
        exit;

        // Add bogus data
        $repository->add(
            PullRequest::fromArray(
                [
                    'title' => 'My great pull request',
                    'repositoryName' => 'LonelyPullRequestsHQ/lonelypullrequests.com',
                    'url' => 'https://github.com/LonelyPullRequestsHQ/lonelypullrequests.com/pull/7',
                    'loneliness' => 50
                ]
            )
        );
        $repository->add(
            PullRequest::fromArray(
                [
                    'title' => 'Another great pull request',
                    'repositoryName' => 'LonelyPullRequestsHQ/lonelypullrequests.com',
                    'url' => 'https://github.com/LonelyPullRequestsHQ/lonelypullrequests.com/pull/7',
                    'loneliness' => 25
                ]
            )
        );
        $repository->add(
            PullRequest::fromArray(
                [
                    'title' => 'A not so lonely pull request',
                    'repositoryName' => 'LonelyPullRequestsHQ/lonelypullrequests.com',
                    'url' => 'https://github.com/LonelyPullRequestsHQ/lonelypullrequests.com/pull/7',
                    'loneliness' => 5
                ]
            )
        );

        return $this->render(
            'LonelyPullRequestsBundle:Default:index.html.twig',
            array('pullRequests' => $repository->all())
        );
    }
}
