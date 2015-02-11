<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Controller;

use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /** @var PullRequestsRepository $repository */
        $repository = $this->get('lonely_pull_requests.repository.pull_requests');

        return $this->render(
            'LonelyPullRequestsBundle:Default:index.html.twig',
            array('pullRequests' => $repository->all())
        );
    }
}
