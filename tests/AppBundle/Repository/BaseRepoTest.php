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

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\PriceRange;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Tests\AppBundle\DatabasePrimer;
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;

class BaseRepoTest extends KernelTestCase
{
    private $em;

    private $repo;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        DatabasePrimer::prime(self::$kernel);

        $fixture = new LoadRepositoryTestData();
        $fixture->setContainer(self::$kernel->getContainer());
        $fixture->load($this->em);

        $this->repo = $this->em->getRepository(PriceRange::class);
    }

    public function testSaveNewObject()
    {
        $priceRange = new PriceRange();
        $priceRange->setMin(1);
        $priceRange->setMax(2);

        $this->repo->save($priceRange);

        $actualPriceRange = $this->repo->find($priceRange->getId());

        $this->em->remove($priceRange);

        $this->assertEquals($priceRange, $actualPriceRange);
    }

    public function testSaveOverwriteObject()
    {
        $priceRange = new PriceRange();
        $priceRange->setMin(1);
        $priceRange->setMax(2);

        $this->repo->save($priceRange);

        $priceRange->setMin(3);
        $priceRange->setMax(4);

        $this->repo->save($priceRange);

        $actualPriceRange = $this->repo->find($priceRange->getId());

        $this->em->remove($priceRange);

        $this->assertEquals($priceRange, $actualPriceRange);
    }

    public function testDelete()
    {
        $priceRange = new PriceRange();
        $priceRange->setMin(1);
        $priceRange->setMax(2);

        $this->em->persist($priceRange);
        $this->em->flush();

        $id = $priceRange->getId();

        $this->repo->delete($priceRange->getId());

        $actualPriceRange = $this->repo->find($id);

        $this->assertNull($actualPriceRange);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}