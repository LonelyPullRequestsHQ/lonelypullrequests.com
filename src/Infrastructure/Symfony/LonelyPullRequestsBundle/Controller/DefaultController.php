<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Controller;

use LonelyPullRequests\Infrastructure\Persistence\DoctrinePullRequestsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /** @var DoctrinePullRequestsRepository $repository */
        $repository = $this->get('lonely_pull_requests.repository.pull_requests');

        return $this->render(
            'LonelyPullRequestsBundle:Default:index.html.twig',
            array('pullRequests' => $repository->all())
        );
    }
}
