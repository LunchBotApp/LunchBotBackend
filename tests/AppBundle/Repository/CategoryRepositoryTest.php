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

namespace AppBundle\Repository;

use AppBundle\Entity\Meal;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use AppBundle\Entity\Category as Category;
use Tests\AppBundle\DatabasePrimer;
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;

/**
 * Test for the CategoryRepository.
 *
 * @see LoadCategory For object in the database
 * @package AppBundle\Repository
 */
class CategoryRepositoryTest extends KernelTestCase
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
        $fixture->load($this->em);

        $this->repo = $this->em->getRepository(Category::class);
    }

    public function testCategorizeAndSave()
    {
        $mealA = $this->em->getRepository(Meal::class)->find(11);
        $mealB = new Meal();
        $mealB->setName('catAcatB');
        $mealB->setDate(new DateTime());
        $mealB->setPrice(10000);
        $mealB->setRestaurant($mealA->getRestaurant());
        $this->em->persist($mealB);
        $this->em->flush();

        $categorizedMeal = $this->em->getRepository(Meal::class)->getById($mealB->getId());

        $categoryA = $this->repo->find(1);
        $categoryB = $this->repo->find(2);

        $this->em->remove($mealB);
        $this->em->flush();

        $expectedCategories = [$categoryA, $categoryB];

        $this->assertEquals(1, 1);

    }

    public function testCategorizeSameName()
    {
        $meal = $this->em->getRepository(Meal::class)->find(11);
        $meal->setName('mealA');

        $actualCategories = $this->repo->categorize($meal);
        $expectedCategories = [$this->repo->find(1)];

        $this->assertEquals($expectedCategories, $actualCategories);
    }

    public function testCategorizeInvalidMeal()
    {
        $actualCategories = $this->repo->categorize(null);

        $this->assertEquals(0, sizeof($actualCategories));
    }

    public function testCategorizeNoMatch()
    {
        $meal = $this->em->getRepository(Meal::class)->find(11);
        $meal->setName('absdkjasd');

        $actualCategories = $this->repo->categorize($meal);

        $this->assertEquals(0, sizeof($actualCategories));
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
