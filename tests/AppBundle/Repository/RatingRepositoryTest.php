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

use AppBundle\Command\LoadRepoTestFixtureCommand;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Rating;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Tests\AppBundle\DatabasePrimer;
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;

class RatingRepositoryTest extends KernelTestCase
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

        $this->repo = $this->em->getRepository(Rating::class);
    }

    public function testGetByUserId()
    {
        $expectedRatings = $this->repo->findBy(['user' => 1]);
        $actualRatings = $this->repo->getAllByUserId(1);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(8, sizeof($actualRatings));
        $this->assertEquals($expectedRatings, $actualRatings);
    }

    public function testGetByUserIdInvalidUser()
    {
        $actualRatings = $this->repo->getAllByUserId(100);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(0, sizeof($actualRatings));
    }


    public function testGetByUserIdNoRatings()
    {
        $actualRatings = $this->repo->getAllByUserId(5);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(0, sizeof($actualRatings));
    }

    public function testGetAllByMealId()
    {
        $expectedRatings = [
            $this->repo->find(1),
            $this->repo->find(9),
            $this->repo->find(17),
            $this->repo->find(25),
        ];
        $actualRatings = $this->repo->getAllByMealId(1);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(4, sizeof($actualRatings));
        $this->assertEquals($expectedRatings, $actualRatings);
    }

    public function testGetAllByMealIdInvalidMeal()
    {
        $actualRatings = $this->repo->getAllByMealId(100);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(0, sizeof($actualRatings));
    }

    public function testGetAllByMealIdNoRatings()
    {
        $actualRatings = $this->repo->getAllByMealId(9);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(0, sizeof($actualRatings));
    }

    public function testGetAllByRestaurantId()
    {
        $expectedRatings = [
            $this->repo->find(1),
            $this->repo->find(2),
            $this->repo->find(9),
            $this->repo->find(10),
            $this->repo->find(17),
            $this->repo->find(18),
            $this->repo->find(25),
            $this->repo->find(26),
        ];
        $actualRatings = $this->repo->getAllByRestaurantId(1);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(8, sizeof($actualRatings));
    }

    public function testGetAllByRestaurantIdInvalidRestaurant()
    {
        $actualRatings = $this->repo->getAllByRestaurantId(100);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(0, sizeof($actualRatings));
    }

    public function testGetAllByRestaurantIdNoRatings()
    {
        $actualRatings = $this->repo->getAllByRestaurantId(5);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(0, sizeof($actualRatings));
    }

    public function testGetPrevious5Star()
    {
        $expectedMeals = [$this->em->getRepository(Meal::class)->find(5)];
        $todayMeals = $this->em->getRepository(Meal::class)->findAll();
        $user = $this->em->getRepository(User::class)->find(1);
        $actualMeals = $this->repo->getPrevious5Star($todayMeals, $user);

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(1, sizeof($actualMeals));
        $this->assertEquals($expectedMeals, $actualMeals);
    }

    public function testGetPrevious5StarInvalidUser()
    {
        $todayMeals = $this->em->getRepository(Meal::class)->findAll();
        $user = $this->em->getRepository(User::class)->find(100);
        $actualMeals = $this->repo->getPrevious5Star($todayMeals, $user);

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetPrevious5StarInvalidMeals()
    {
        $todayMeals = [null, null, null];
        $user = $this->em->getRepository(User::class)->find(1);
        $actualMeals = $this->repo->getPrevious5Star($todayMeals, $user);

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetPrevious5StarNo5Star()
    {
        $todayMeals = $this->em->getRepository(Meal::class)->findAll();
        $user = $this->em->getRepository(User::class)->find(5);
        $actualMeals = $this->repo->getPrevious5Star($todayMeals, $user);

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}