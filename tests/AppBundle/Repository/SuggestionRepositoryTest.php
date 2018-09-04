<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\GroupSuggestion;
use AppBundle\Entity\PriceRange;
use AppBundle\Entity\Settings;
use AppBundle\Entity\Suggestion;
use AppBundle\Entity\User;
use AppBundle\Entity\SoloSuggestion;
use AppBundle\Entity\Address;
use AppBundle\Entity\Rating;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Tests\AppBundle\DatabasePrimer;
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;

class SuggestionRepositoryTest extends KernelTestCase
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

        $this->repo = $this->em->getRepository(Suggestion::class);
    }

    public function testSoloSuggestionNoSettings()
    {
        $user = $this->em->getRepository(User::class)->find(3);
        $suggestion = new SoloSuggestion();
        $suggestion->setUser($user);
        $suggestion->setSettings(new Settings());
        $bestMeals = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));

    }

    public function testGroupSuggestionNoSettings()
    {
        $users = [$this->em->getRepository(User::class)->find(3),
            $this->em->getRepository(User::class)->find(4)];

        $suggestion = new GroupSuggestion();
        $suggestion->setUsers($users);


        //TODO uncomment this part if provider is set up for Restaurants and delete part below
/*        $bestRestaurant = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestRestaurant));
        $this->assertEquals(3, sizeof($bestRestaurant));
        $expectedRestaurantNames = ['RestaurantC', 'RestaurantB', 'RestaurantD'];
        $actualRestaurantNames = [];
        for ($i = 0; $i < 3; $i++) {
            $actualRestaurantNames[$i] = $bestRestaurant[$i]->getName();
        }

        $this->assertEquals($expectedRestaurantNames, $actualRestaurantNames);*/

        $bestMeal = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeal));
        $this->assertEquals(0, sizeof($bestMeal));

    }

    public function testSoloSuggestionWithIndividualSettings()
    {
        $user = $this->em->getRepository(User::class)->find(1);
        $suggestion = new SoloSuggestion();
        $suggestion->setUser($user);
        $bestMeals = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));
    }

    public function testGroupSuggestionWithIndividualSettings()
    {
        $priceRangeA = new PriceRange();
        $priceRangeA->setMin(1);
        $priceRangeA->setMax(4);
        $priceRangeB = new PriceRange();
        $priceRangeB->setMin(3);
        $priceRangeB->setMax(6);
        $userA = $this->em->getRepository(User::class)->find(1);
        $userA->getSettings()->setPriceRange($priceRangeA);
        $userB = $this->em->getRepository(User::class)->find(2);
        $userB->getSettings()->setPriceRange($priceRangeB);
        $users = [$userA, $userB];

        $suggestion = new GroupSuggestion();
        $suggestion->setUsers($users);

        //TODO uncomment this part if provider is set up for Restaurants and delete part below
/*        $bestRestaurant = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestRestaurant));
        $this->assertEquals(3, sizeof($bestRestaurant));

        $expectedRestaurantNames = ['RestaurantB', 'RestaurantC', 'RestaurantA'];
        $actualRestaurantNames = [];
        for ($i = 0; $i < 3; $i++) {
            $actualRestaurantNames[$i] = $bestRestaurant[$i]->getName();
        }

        $this->assertEquals($expectedRestaurantNames, $actualRestaurantNames);*/

        $bestMeal = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeal));
        $this->assertEquals(0, sizeof($bestMeal));
    }

    public function testSoloSuggestionWithGlobalSettings()
    {
        $user = $this->em->getRepository(User::class)->find(1);
        $suggestion = new SoloSuggestion();
        $suggestion->setUser($user);
        $location = $this->em->getRepository(Address::class)->find(4);
        $priceRange = new PriceRange();
        $priceRange->setMin(5);
        $priceRange->setMax(7);
        $settings = new Settings();
        $settings->setLocation($location);
        $settings->setPriceRange($priceRange);
        $suggestion->setSettings($settings);
        $bestMeals = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));
    }

    public function testGroupSuggestionWithGlobalSettings()
    {
        $users = [$this->em->getRepository(User::class)->find(3),
            $this->em->getRepository(User::class)->find(4)];

        $suggestion = new GroupSuggestion();
        $suggestion->setUsers($users);

        $location = $this->em->getRepository(Address::class)->find(1);
        $priceRange = new PriceRange();
        $priceRange->setMin(3);
        $priceRange->setMax(6);
        $settings = new Settings();
        $settings->setLocation($location);
        $settings->setPriceRange($priceRange);
        $suggestion->setSettings($settings);

        //TODO uncomment this part if provider is set up for Restaurants and delete part below
/*        $bestRestaurant = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestRestaurant));
        $this->assertEquals(3, sizeof($bestRestaurant));

        $expectedRestaurantNames = ['RestaurantC', 'RestaurantB', 'RestaurantA'];
        $actualRestaurantNames = [];
        for ($i = 0; $i < 3; $i++) {
            $actualRestaurantNames[$i] = $bestRestaurant[$i]->getName();
        }
        $this->assertEquals($expectedRestaurantNames, $actualRestaurantNames);*/

        $bestMeal = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeal));
        $this->assertEquals(0, sizeof($bestMeal));
    }

    public function testSoloSuggestionWithGlobalPriceRangeIndividualLocationSettings()
    {
        $user = $this->em->getRepository(User::class)->find(1);
        $suggestion = new SoloSuggestion();
        $suggestion->setUser($user);
        $priceRange = new PriceRange();
        $priceRange->setMin(5);
        $priceRange->setMax(7);
        $settings = new Settings();
        $settings->setPriceRange($priceRange);
        $suggestion->setSettings($settings);
        $bestMeals = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));
    }

    public function testGroupSuggestionWithGlobalPriceRangeIndividualLocationSettings()
    {
        $users = [$this->em->getRepository(User::class)->find(3),
            $this->em->getRepository(User::class)->find(4)];
        $suggestion = new GroupSuggestion();
        $suggestion->setUsers($users);
        $priceRange = new PriceRange();
        $priceRange->setMin(3);
        $priceRange->setMax(4);
        $settings = new Settings();
        $settings->setPriceRange($priceRange);
        $suggestion->setSettings($settings);

        //TODO uncomment this part if provider is set up for Restaurants and delete part below
/*        $bestRestaurants = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestRestaurants));
        $this->assertEquals(3, sizeof($bestRestaurants));

        $expectedRestaurantNames = ['RestaurantB','RestaurantA','RestaurantC'];
        for ($i = 0; $i < 3; $i++) {
            $actualRestaurantNames[$i] = $bestRestaurants[$i]->getName();
        }
        $this->assertEquals($expectedRestaurantNames, $actualRestaurantNames);*/

        $bestMeals = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));

    }

    public function testSoloSuggestionWithGlobalLocationIndividualPriceSettings()
    {
        $user = $this->em->getRepository(User::class)->find(1);
        $user->getSettings()->setLocation($this->em->getRepository(Address::class)->find(4));
        $suggestion = new SoloSuggestion();
        $suggestion->setUser($user);
        $settings = new Settings();
        $settings->setLocation($this->em->getRepository(Address::class)->find(1));
        $suggestion->setSettings($settings);
        $bestMeals = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));
    }

    public function testGroupSuggestionWithGlobalLocationIndividualPriceSettings()
    {
        $location = $this->em->getRepository(Address::class)->find(4);
        $userA = $this->em->getRepository(User::class)->find(1);
        $userA->getSettings()->setLocation($location);
        $userB = $this->em->getRepository(User::class)->find(2);
        $userB->getSettings()->setLocation($location);
        $users = [$userA, $userB];
        $suggestion = new GroupSuggestion();
        $suggestion->setUsers($users);
        $settings = new Settings();
        $settings->setLocation($this->em->getRepository(Address::class)->find(1));
        $suggestion->setSettings($settings);

        //TODO uncomment this part if provider is set up for Restaurants and delete part below
/*        $bestRestaurant = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestRestaurant));
        $this->assertEquals(3, sizeof($bestRestaurant));

        $expectedRestaurantNames = ['RestaurantC', 'RestaurantB', 'RestaurantA'];
        for ($i = 0; $i < 3; $i++) {
            $actualRestaurantNames[$i] = $bestRestaurant[$i]->getName();
        }
        $this->assertEquals($expectedRestaurantNames, $actualRestaurantNames);*/

        $bestMeal = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeal));
        $this->assertEquals(0, sizeof($bestMeal));
    }

    /**
     * The requester has a different city than the other people of the suggestion.
     * The city of the requester should be used.
     */
    public function testGroupSuggsestionRequesterDifferentCity()
    {
        $location = $this->em->getRepository(Address::class)->find(4);
        $userA = $this->em->getRepository(User::class)->find(1);
        $userA->setSettings(new Settings());
        $userA->getSettings()->setLocation($location);
        $userB = $this->em->getRepository(User::class)->find(2);
        $userB->setSettings(new Settings());
        $suggestion = new GroupSuggestion();
        $suggestion->setUsers([$userA, $userB]);

        //TODO uncomment this part if provider is set up for Restaurants and delete part below
/*        $bestRestaurant = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestRestaurant));
        $this->assertEquals(3, sizeof($bestRestaurant));

        $expectedRestaurantNames = ['RestaurantD', 'RestaurantA', 'RestaurantB'];
        for ($i = 0; $i < 3; $i++) {
            $actualRestaurantNames[$i] = $bestRestaurant[$i]->getName();
        }
        $this->assertEquals($expectedRestaurantNames, $actualRestaurantNames);*/

        $bestMeal = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeal));
        $this->assertEquals(0, sizeof($bestMeal));
    }

    public function testSoloSuggestionNoSettingsUserNoRatings()
    {
        $user = $this->em->getRepository(User::class)->find(5);
        $suggestion = new SoloSuggestion();
        $suggestion->setUser($user);
        $bestMeals = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));
    }

    public function testSoloSuggestion3Previous5Star()
    {
        $ratingRepo = $this->em->getRepository(Rating::class);
        $ratingA = $ratingRepo->find(1);
        $oldValueA = $ratingA->getValue();
        $ratingA->setValue(5);
        $this->em->persist($ratingA);
        $ratingB = $ratingRepo->find(7);
        $oldValueB = $ratingB->getValue();
        $ratingB->setValue(5);
        $this->em->persist($ratingB);
        $this->em->flush();

        $user = $this->em->getRepository(User::class)->find(1);
        $oldSettings = $user->getSettings();
        $user->setSettings(new Settings());
        $suggestion = new SoloSuggestion();
        $suggestion->setUser($user);

        $bestMeals = $this->repo->getBestMatch($suggestion);

        $ratingA->setValue($oldValueA);
        $this->em->persist($ratingA);
        $ratingB->setValue($oldValueB);
        $this->em->persist($ratingB);
        $user->setSettings($oldSettings);
        $this->em->flush();

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));
    }

    public function testSoloSuggestion4Previous5Star()
    {
        $ratingRepo = $this->em->getRepository(Rating::class);
        $ratingA = $ratingRepo->find(1);
        $oldValueA = $ratingA->getValue();
        $ratingA->setValue(5);
        $this->em->persist($ratingA);
        $ratingB = $ratingRepo->find(7);
        $oldValueB = $ratingB->getValue();
        $ratingB->setValue(5);
        $this->em->persist($ratingB);
        $ratingC = $ratingRepo->find(8);
        $oldValueC = $ratingC->getValue();
        $ratingC->setValue(5);
        $this->em->persist($ratingC);
        $this->em->flush();

        $user = $this->em->getRepository(User::class)->find(1);
        $oldSettings = $user->getSettings();
        $user->setSettings(new Settings());
        $suggestion = new SoloSuggestion();
        $suggestion->setUser($user);

        $bestMeals = $this->repo->getBestMatch($suggestion);

        $ratingA->setValue($oldValueA);
        $this->em->persist($ratingA);
        $ratingB->setValue($oldValueB);
        $this->em->persist($ratingB);
        $ratingC->setValue($oldValueC);
        $this->em->persist($ratingC);
        $user->setSettings($oldSettings);
        $this->em->flush();

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));
    }

    public function testSoloSuggestionPrevious5StarDoesNotFitSettings()
    {
        $user = $this->em->getRepository(User::class)->find(1);
        $user->getSettings()->getPriceRange()->setMin(1);
        $user->getSettings()->getPriceRange()->setMax(3);

        $suggestion = new SoloSuggestion();
        $suggestion->setUser($user);

        $bestMeals = $this->repo->getBestMatch($suggestion);

        $this->assertTrue(is_array($bestMeals));
        $this->assertEquals(0, sizeof($bestMeals));
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}