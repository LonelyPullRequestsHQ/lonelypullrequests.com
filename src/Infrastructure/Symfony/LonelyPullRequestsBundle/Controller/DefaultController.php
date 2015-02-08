<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Controller;

use LonelyPullRequests\Domain\PullRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this->get('lonely_pull_requests.repository.pull_requests');

        // Add bogus data
        $repository->add(
            PullRequest::fromArray(
                [
                    'summary' => 'My great pull request',
                    'repositoryName' => 'LonelyPullRequestsHQ/lonelypullrequests.com',
                    'url' => 'https://github.com/LonelyPullRequestsHQ/lonelypullrequests.com/pull/7',
                    'loneliness' => 50
                ]
            )
        );
        $repository->add(
            PullRequest::fromArray(
                [
                    'summary' => 'Another great pull request',
                    'repositoryName' => 'LonelyPullRequestsHQ/lonelypullrequests.com',
                    'url' => 'https://github.com/LonelyPullRequestsHQ/lonelypullrequests.com/pull/7',
                    'loneliness' => 25
                ]
            )
        );
        $repository->add(
            PullRequest::fromArray(
                [
                    'summary' => 'A not so lonely pull request',
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
