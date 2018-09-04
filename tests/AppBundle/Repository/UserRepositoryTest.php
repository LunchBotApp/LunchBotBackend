<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Address;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Rating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Tests\AppBundle\DatabasePrimer;
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;

use AppBundle\Entity\User;

class UserRepositoryTest extends KernelTestCase
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

        $this->repo = $this->em->getRepository(User::class);
    }

    public function testGetAllMatchPointsNoSettings()
    {
        $todaysMeals = $this->em->getRepository(Meal::class)->getAllTodayMeals();
        $user = $this->em->getRepository(User::class)->find(3);
        $actualPoints = $this->repo->getAllMatchPoints($todaysMeals, $user, true, null);

        $this->assertTrue(is_array($actualPoints));
        $this->assertEquals(0, sizeof($actualPoints));

        $expectedPoints = [1, 2, 3, 4, 5, 3, 1, 4, 2.5, 3.25, 0];
    }

    public function testGetAllMatchPointsWithIndividualSettings()
    {
        $todaysMeals = $this->em->getRepository(Meal::class)->getAllTodayMeals();
        $user = $this->em->getRepository(User::class)->find(1);
        $actualPoints = $this->repo->getAllMatchPoints($todaysMeals, $user, true, null);

        $this->assertTrue(is_array($actualPoints));
        $this->assertEquals(0, sizeof($actualPoints));

        $expectedPoints = [1, 2, 3, 4, 5, 0, 0, 0, 0, 0, 0];
    }

    public function testGetAllMatchPointsWithGlobalSettings()
    {
        $todaysMeals = $this->em->getRepository(Meal::class)->getAllTodayMeals();
        $user = $this->em->getRepository(User::class)->find(1);
        $location = $this->em->getRepository(Address::class)->find(4);
        $actualPoints = $this->repo->getAllMatchPoints($todaysMeals, $user, false, $location->getCity());

        $this->assertTrue(is_array($actualPoints));
        $this->assertEquals(0, sizeof($actualPoints));

        $expectedPoints = [0, 0, 0, 0, 0, 0, 1, 4, 0, 0, 0];
    }

    public function testGetAllMatchPointsUserNoRatings()
    {
        $todaysMeals = $this->em->getRepository(Meal::class)->getAllTodayMeals();
        $user = $this->em->getRepository(User::class)->find(5);
        $actualPoints = $this->repo->getAllMatchPoints($todaysMeals, $user, true, null);

        $this->assertTrue(is_array($actualPoints));
        $this->assertEquals(0, sizeof($actualPoints));

        $expectedPoints = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    }

    public function testGetByUserId()
    {
        $expectedUser = $this->repo->findOneBy(['userId' => 'userIdA']);
        $actualUser = $this->repo->getByUserId('userIdA');

        $this->assertInstanceOf(User::class, $actualUser);
    }

    public function testGetByUserIdInvalidUserId()
    {
        $actualUser = $this->repo->getByUserId('abcdefg');

        $this->assertNull($actualUser);
    }

    public function testGetAverageRating()
    {
        $actualAverage = $this->repo->getAverageRating(1);

        $this->assertEquals(2.875, $actualAverage);
    }

    public function testGetAverageRatingInvalidUser()
    {
        $actualAverage = $this->repo->getAverageRating(100);

        $this->assertEquals(0, $actualAverage);
    }

    public function testGetAverageRatingUserNoRatings()
    {
        $actualAverage = $this->repo->getAverageRating(5);

        $this->assertEquals(0, $actualAverage);
    }

    public function testGetRatings()
    {
        $expectedRatings = $this->em->getRepository(Rating::class)->getAllByUserId(1);
        $actualRatings = $this->repo->getRatings(1);

        $this->assertEquals($expectedRatings, $actualRatings);
    }

    public function testGetRatingsUserNoRatings()
    {
        $actualRatings = $this->repo->getRatings(5);

        $this->assertEquals(0, sizeof($actualRatings));
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}