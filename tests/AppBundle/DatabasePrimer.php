<?php

namespace Tests\AppBundle;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class DatabasePrimer
 *
 * @package Tests\AppBundle
 */
class DatabasePrimer
{
    /**
     * @param KernelInterface $kernel
     */
    public static function prime(KernelInterface $kernel)
    {
        // Make sure we are in the test environment
        if ('test' !== $kernel->getEnvironment()) {
            throw new \LogicException('Primer must be executed in the test environment');
        }

        // Get the entity manager as well as the user manager from the service container
        $container  = $kernel->getContainer();
        $em         = $container->get('doctrine.orm.entity_manager');
        $schemaTool = new SchemaTool($em);

        // Drop the old database ...
        $schemaTool->dropDatabase();

        // ... and then recreate it by running the schema update tool using our entity metadata
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->updateSchema($metadatas);
    }
}
