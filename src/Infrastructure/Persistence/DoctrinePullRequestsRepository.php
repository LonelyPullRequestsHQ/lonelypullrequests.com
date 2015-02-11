<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use Doctrine\ORM\EntityRepository;
use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\PullRequests;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use LonelyPullRequests\Domain\RepositoryName;

final class DoctrinePullRequestsRepository extends EntityRepository implements PullRequestsRepository
{
    /**
     * @param PullRequest $pullRequest
     *
     * @return PullRequests
     */
    public function add(PullRequest $pullRequest)
    {
        $em = $this->getEntityManager();
        $em->persist($pullRequest);
        $em->flush();

        return $this->all();
    }

    /**
     * {{@inheritdoc}}
     */
    public function getByRepositoryName(RepositoryName $repositoryName)
    {
        return $this->findOneBy([
            'repositoryName' => $repositoryName->toString(),
        ]);
    }

    /**
     * @return PullRequests
     */
    public function all()
    {
        return new PullRequests($this->findAll());
    }
}
