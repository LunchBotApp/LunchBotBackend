<?php
namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Meal;
use AppBundle\Entity\Rating;
use AppBundle\Entity\Restaurant;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Tests\AppBundle\DatabasePrimer;
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;

class RestaurantRepositoryTest  extends KernelTestCase
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

        $this->repo = $this->em->getRepository(Restaurant::class);
    }

    public function testGetAverageRating()
    {
        $actualAverage = $this->repo->getAverageRating(1);

        $this->assertEquals(1.5, $actualAverage);
    }

    public function testGetAverageRatingInvalidRestaurant()
    {
        $actualAverage = $this->repo->getAverageRating(100);

        $this->assertEquals(0, $actualAverage);
    }

    public function testGetAverageRatingNoRatings()
    {
        $actualAverage = $this->repo->getAverageRating(5);

        $this->assertEquals(0, $actualAverage);
    }

    public function testGetRatings()
    {
        $expectedRatings = [
            $this->em->getRepository(Rating::class)->find(1),
            $this->em->getRepository(Rating::class)->find(2),
            $this->em->getRepository(Rating::class)->find(9),
            $this->em->getRepository(Rating::class)->find(10),
            $this->em->getRepository(Rating::class)->find(17),
            $this->em->getRepository(Rating::class)->find(18),
            $this->em->getRepository(Rating::class)->find(25),
            $this->em->getRepository(Rating::class)->find(26),
        ];

        $actualRatings = $this->repo->getRatings(1);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(8, sizeof($actualRatings));
    }

    public function testGetRatingsInvalidRestaurant()
    {
        $actualRatings = $this->em->getRepository(Rating::class)->getAllByRestaurantId(100);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(0, sizeof($actualRatings));
    }

    public function testGetRatingsNoRatings()
    {
        $actualRatings = $this->em->getRepository(Rating::class)->getAllByRestaurantId(5);

        $this->assertTrue(is_array($actualRatings));
        $this->assertEquals(0, sizeof($actualRatings));
    }

    public function testgetTodayMeals()
    {
        $expectedMeals = [
            $this->em->getRepository(Meal::class)->find(1),
            $this->em->getRepository(Meal::class)->find(2)];
        $actualMeals = $this->repo->getTodayMeals(1);

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetTodayMealsInvalidRestaurant()
    {
        $actualMeals = $this->repo->getTodayMeals(100);

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetTodayMealsNoMeals()
    {
        $mealA =  $this->em->getRepository(Meal::class)->find(1);
        $oldDateA = clone $mealA->getDate();
        $date = new DateTime();
        $date->setDate(1, 1, 1);
        $mealA->setDate($date);
        $this->em->persist($mealA);
        $mealB =  $this->em->getRepository(Meal::class)->find(2);
        $oldDateB = clone $mealB->getDate();
        $date = new DateTime();
        $date->setDate(1, 1, 1);
        $mealB->setDate($date);
        $this->em->persist($mealB);
        $this->em->flush();

        $actualMeals = $this->repo->getTodayMeals(1);

        $mealA->setDate($oldDateA);
        $mealB->setDate($oldDateB);
        $this->em->persist($mealA);
        $this->em->persist($mealB);
        $this->em->flush();

        $this->assertTrue(is_array($actualMeals));
        $this->assertEquals(0, sizeof($actualMeals));
    }

    public function testGetAllRestaurantsByCity()
    {
        $expectedRestaurants = [
            $this->repo->find(1),
            $this->repo->find(2),
            $this->repo->find(3),
            $this->repo->find(5),
            ];

        $actualRestaurants = $this->repo->getAllRestaurantsByCity(1);

        $this->assertTrue(is_array($actualRestaurants));
        $this->assertEquals(4, sizeof($actualRestaurants));
        $this->assertEquals($expectedRestaurants, $actualRestaurants);
    }

    public function testGetAllRestaurantsByCityInvalidCity()
    {
        $actualRestaurants = $this->repo->getAllRestaurantsByCity(100);

        $this->assertTrue(is_array($actualRestaurants));
        $this->assertEquals(0, sizeof($actualRestaurants));
    }

    public function testGetAllRestaurant()
    {
        $expectedRestaurants = [
            $this->repo->find(1),
            $this->repo->find(2),
            $this->repo->find(3),
            $this->repo->find(4),
            $this->repo->find(5),
        ];

        $actualRestaurants = $this->repo->getAllRestaurants();

        $this->assertTrue(is_array($actualRestaurants));
        $this->assertEquals(5, sizeof($actualRestaurants));
        $this->assertEquals($expectedRestaurants, $actualRestaurants);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}