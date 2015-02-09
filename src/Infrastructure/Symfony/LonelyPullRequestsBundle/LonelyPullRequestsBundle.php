<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LonelyPullRequestsBundle extends Bundle
{
    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        $customTypes = array(
            'loneliness' => '\LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\LonelinessType',
            'repositoryName' => '\LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\RepositoryNameType',
            'summary' => '\LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\SummaryType',
            'url' => '\LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Type\UrlType'
        );

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $conn = $em->getConnection();

        // Add value-objects as Doctrine DBAL custom types
        foreach($customTypes as $typeName => $typeClass) {
            Type::addType($typeName, $typeClass);
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping($typeName, $typeName);
        }
    }
}
