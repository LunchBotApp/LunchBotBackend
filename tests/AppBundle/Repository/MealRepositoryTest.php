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

use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Tests\AppBundle\DatabasePrimer;
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;

class MealRepositoryTest extends KernelTestCase
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

        $this->repo = $this->em->getRepository(Meal::class);
    }

    public function testGetDifferentByName()
    {
        $expectedMeal = $this->repo->find(1);
        $meal = $this->repo->find(11);
        $meal->setName('mealA');
        $actualMeal = $this->repo->GetDifferentByName($meal);

        $this->assertInstanceOf(Meal::class, $actualMeal);
        $this->assertEquals($expectedMeal, $actualMeal);
    }

    public function testGetDifferentByNameInvalidName()
    {
        $meal = $this->repo->find(11);
        $meal->setName('invalid');
        $actualMeal = $this->repo->GetDifferentByName($meal);

        $this->assertNull($actualMeal);
    }

    public function testGetAllUpcoming()
    {
        $mealA = $this->repo->find(1);
        $oldDateA = clone $mealA->getDate();
        $date = new DateTime();
        $date->setDate(9999, 1, 1);
        $mealA->setDate($date);
        $this->em->persist($mealA);
        $mealB = $this->repo->find(2);
        $oldDateB = clone $mealB->getDate();
        $date = new DateTime();
        $date->setDate(1, 1, 1);
        $mealB->setDate($date);
        $this->em->persist($mealB);
        $mealC = $this->repo->find(3);
        $oldDateC = clone $mealC->getDate();
        $date = new DateTime();
        $date->setDate(1, 1, 1);
        $mealC->setDate($date);
        $this->em->persist($mealC);
        $this->em->flush();

        $expectedMeals = array_slice($this->repo->findAll(), 3);
        array_push($expectedMeals, $mealA);
        $actualMeals = $this->repo->getAllUpcoming();

        $mealA->setDate($oldDateA);
        $mealB->setDate($oldDateB);
        $mealC->setDate($oldDateC);
        $this->em->persist($mealA);
        $this->em->persist($mealB);
        $this->em->persist($mealC);
        $this->em->flush();

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(1, sizeof($actualMeals));
    }

    public function testGetAllUpcomingNoUpcoming()
    {
        $date = new DateTime();
        $date->setDate(1, 1, 1);

        for ($i = 1; $i <= 11; $i++) {
            $meal = $this->repo->find($i);
            $oldDate = clone $meal->getDate();
            $meal->setDate($date);
            $this->em->persist($meal);
        }
        $this->em->flush();

        $actualMeals = $this->repo->getAllUpcoming();

        for ($i = 1; $i <=   11; $i++) {
            $meal = $this->repo->find($i);
            $meal->setDate($oldDate);
            $this->em->persist($meal);
        }
        $this->em->flush();

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetAllUncategorized()
    {
        $actualMeals = $this->repo->getAllUncategorized();

        $expectedMeals = [$this->repo->find(11)];

        $this->assertTrue(is_array($expectedMeals));
        $this->assertEquals(0, sizeof($actualMeals));
        $this->assertEquals($actualMeals, $actualMeals);
    }

    public function testGetAllUncategorizedNoUncategorized()
    {
        $meal = $this->repo->find(11);
        $category = $this->em->getRepository(Category::class)->find(1);
        $meal->setCategories([$category]);
        $this->em->persist($meal);
        $this->em->flush();

        $actualMeals = $this->repo->getAllUncategorized();

        $meal->setCategories(null);
        $this->em->persist($meal);
        $this->em->flush();

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetAllTodayMealsByRestaurant()
    {
        $expectedMeals = [$this->repo->find(1), $this->repo->find(2)];
        $actualMeals = $this->repo->getAllTodayMealsByRestaurant(1);

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetAllTodayMealsByRestaurantInvalidRestaurant()
    {
        $actualMeals = $this->repo->getAllTodayMealsByRestaurant(111);

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetAllTodayMealsByRestaurantNoMeals()
    {
        $mealA = $this->repo->find(1);
        $oldDateA = clone $mealA->getDate();
        $date = new DateTime();
        $date->setDate(1, 1, 1);
        $mealA->setDate($date);
        $this->em->persist($mealA);
        $mealB = $this->repo->find(2);
        $oldDateB = clone $mealB->getDate();
        $date = new DateTime();
        $date->setDate(1, 1, 1);
        $mealB->setDate($date);
        $this->em->persist($mealB);
        $this->em->flush();

        $actualMeals = $this->repo->getAllTodayMealsByRestaurant(1);

        $mealA->setDate($oldDateA);
        $mealB->setDate($oldDateB);
        $this->em->persist($mealA);
        $this->em->persist($mealB);
        $this->em->flush();

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetAllTodayMeals()
    {
        $mealA = $this->repo->find(1);
        $oldDateA = clone $mealA->getDate();
        $date = new DateTime();
        $date->setDate(1, 1, 1);
        $mealA->setDate($date);
        $this->em->persist($mealA);
        $mealB = $this->repo->find(2);
        $oldDateB = clone $mealB->getDate();
        $date = new DateTime();
        $date->setDate(1, 1, 1);
        $mealB->setDate($date);
        $this->em->persist($mealB);
        $mealC = $this->repo->find(3);
        $oldDateC = clone $mealC->getDate();
        $date = new DateTime();
        $date->setDate(1, 1, 1);
        $mealC->setDate($date);
        $this->em->persist($mealC);
        $this->em->flush();

        $expectedMeals = array_slice($this->repo->findAll(), 3);
        $actualMeals = $this->repo->getAllTodayMeals();

        $mealA->setDate($oldDateA);
        $mealB->setDate($oldDateB);
        $mealC->setDate($oldDateC);
        $this->em->persist($mealA);
        $this->em->persist($mealB);
        $this->em->persist($mealC);
        $this->em->flush();

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