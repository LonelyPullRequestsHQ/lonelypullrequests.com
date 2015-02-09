<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle;

use Doctrine\DBAL\Types\Type;
use LonelyPullRequests\Infrastructure\Symfony\Type\RepositoryNameType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LonelyPullRequestsBundle extends Bundle
{
    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        echo '<pre>';

        Type::addType('repositoryName', 'LonelyPullRequests\Infrastructure\Symfony\Type\RepositoryTypeName');
        var_dump(Type::getTypesMap());
        $this->container->get('doctrine.orm.default_entity_manager')->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('repositoryName', 'repositoryName');

        /**
         * ClassNotFoundException in Type.php line 174:
        Attempted to load class "RepositoryTypeName" from namespace "LonelyPullRequests\Infrastructure\Symfony\Type".
        Did you forget a "use" statement for another namespace?
         *
         * :(
         */
        var_dump(Type::getTypesMap());exit;

        //var_dump(class_exists('\LonelyPullRequests\Infrastructure\Symfony\Type\RepositoryNameType'));

        //Type::addType('repositoryName', '\LonelyPullRequests\Infrastructure\Symfony\Type\RepositoryNameType');

        /** @var EntityManager $em */
        //$em = $this->container->get('doctrine.orm.default_entity_manager');

        //$conn = $em->getConnection();
        //$conn->getDatabasePlatform()->registerDoctrineTypeMapping('repositoryName', 'repositoryName');
    }
}
