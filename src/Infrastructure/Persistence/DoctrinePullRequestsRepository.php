<?php

namespace LonelyPullRequests\Infrastructure\Persistence;

use Doctrine\ORM\EntityRepository;
use LonelyPullRequests\Domain\PullRequest;
use LonelyPullRequests\Domain\PullRequests;
use LonelyPullRequests\Domain\Repository\PullRequestsRepository;
use LonelyPullRequests\Domain\RepositoryName;
use LonelyPullRequests\Domain\Title;

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
        $em->merge($pullRequest);
        $em->flush();

        return $this->all();
    }

    /**
     * @param PullRequest $pullRequest
     * @return boolean
     */
    public function has(PullRequest $pullRequest)
    {
        return ($this->getByRepositoryNameTitle($pullRequest->repositoryName(), $pullRequest->title()) !== null);
    }

    /**
     * @param PullRequest $pullRequest
     *
     * @return boolean
     */
    public function remove(PullRequest $pullRequest)
    {
        $entity = $this->getByRepositoryNameTitle($pullRequest->repositoryName(), $pullRequest->title());
        if($entity !== null) {
            $entityManager = $this->getEntityManager();
            $entityManager->remove($entity);
            $entityManager->flush();
        }

        return true;
    }

    /**
     * {{@inheritdoc}}
     */
    public function getByRepositoryNameTitle(RepositoryName $repositoryName, Title $title)
    {
        return $this->findOneBy([
            'repositoryName' => $repositoryName,
            'title' => $title,
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
