<?php
/**
 * This file is part of LunchBotBackend.
 *
 * Copyright (c) 2018 Benoît Frisch, Haris Dzogovic, Philipp Machauer, Pierre Bonert, Xiang Rong Lin
 *
 * LunchBotBackend is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 *
 * LunchBotBackend is distributed in the hope that it will be useful,but WITHOUT ANY WARRANTY; without even the implied warranty ofMERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with LunchBotBackend If not, see <http://www.gnu.org/licenses/>.
 *
 */

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
