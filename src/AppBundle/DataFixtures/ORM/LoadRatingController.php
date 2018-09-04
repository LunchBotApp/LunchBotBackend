<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Address;
use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use AppBundle\Entity\GroupSuggestion;
use AppBundle\Entity\Language;
use AppBundle\Entity\Meal;
use AppBundle\Entity\MealChoice;
use AppBundle\Entity\PriceRange;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Settings;
use AppBundle\Entity\SoloSuggestion;
use AppBundle\Entity\User;
use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadRatingController
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadRatingController implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $country = new Country();
        $country->setName('Deutschland1');
        $manager->persist($country);

        $cityA =  new City();
        $cityA->setName('Karlsruhe1');
        $cityA->setCountry($country);
        $manager->persist($cityA);

        $addressA = new Address();
        $addressA->setCountry($country);
        $addressA->setCity($cityA);
        $addressA->setStreet('Abcstraße');
        $addressA->setNumber(1);
        $addressA->setCode(76131);
        $manager->persist($addressA);

        $addressB = new Address();
        $addressB->setCountry($country);
        $addressB->setCity($cityA);
        $addressB->setStreet('Defstraße');
        $addressB->setNumber(27);
        $addressB->setCode(76133);
        $manager->persist($addressB);

        $addressC = new Address();
        $addressC->setCountry($country);
        $addressC->setCity($cityA);
        $addressC->setStreet('Ghistraße');
        $addressC->setNumber(63);
        $addressC->setCode(76185);
        $manager->persist($addressC);

        $restaurantA = new Restaurant();
        $restaurantA->setName('RestaurantA');
        $restaurantA->setWebsite('restaurant.de');
        $restaurantA->setPhone('0123456789');
        $restaurantA->setEmail('restaurant@email.de');
        $restaurantA->setAddress($addressA);
        $manager->persist($restaurantA);

        $mealA = new Meal();
        $mealA->setName('mealA');
        $mealA->setDate(new DateTime());
        $mealA->setPrice(1);
        $mealA->setRestaurant($restaurantA);
        $manager->persist($mealA);

        $languageA = new Language();
        $languageA->setName('Deutsch');
        $languageA->setLocale('ger');
        $manager->persist($languageA);

        $languageB = new Language();
        $languageB->setName('English');
        $languageB->setLocale('eng');
        $manager->persist($languageB);

        $priceRangeA = new PriceRange();
        $priceRangeA->setMin(1);
        $priceRangeA->setMax(5);
        $manager->persist($priceRangeA);

        $priceRangeB = new PriceRange();
        $priceRangeB->setMin(4);
        $priceRangeB->setMax(8);
        $manager->persist($priceRangeB);

        $settingsA = new Settings();
        $settingsA->setPriceRange($priceRangeA);
        $settingsA->setLocation($addressC);
        $settingsA->setDistance(2000);
        $settingsA->setLanguage($languageA);
        $manager->persist($settingsA);

        $settingsB = new Settings();
        $settingsB->setPriceRange($priceRangeB);
        $settingsB->setLocation($addressA);
        $settingsB->setDistance(500);
        $settingsB->setLanguage($languageB);
        $manager->persist($settingsB);

        $settingsC = new Settings();
        $manager->persist($settingsC);

        $userA = new User();
        $userA->setUserId('userIdA');
        $userA->setSettings($settingsA);
        $manager->persist($userA);

        $userB = new User();
        $userB->setUserId('userIdB');
        $userB->setSettings($settingsB);
        $manager->persist($userB);

        $userC = new User();
        $userC->setUserId('userIdC');
        $userC->setSettings($settingsC);
        $manager->persist($userC);

        $mealChoice1 = new MealChoice();
        $mealChoice1->setMeal($mealA);
        $mealChoice1->setUser($userA);
        $manager->persist($mealChoice1);

        $suggestion1 = new SoloSuggestion();
        $suggestion1->setTimestamp(new \DateTime());
        $suggestion1->setSuggestions([$mealA]);
        $suggestion1->setUser($userA);
        $suggestion1->setMealChoices([$mealChoice1]);
        $manager->persist($suggestion1);

        $mealChoice1->setSuggestion($suggestion1);
        $manager->persist($mealChoice1);

        $mealChoice2 = new MealChoice();
        $mealChoice2->setUser($userB);
        $manager->persist($mealChoice2);

        $suggestion2 = new SoloSuggestion();
        $suggestion2->setTimestamp(new \DateTime());
        $suggestion2->setSuggestions([$mealA]);
        $suggestion2->setUser($userB);
        $suggestion1->setMealChoices([$mealChoice2]);
        $manager->persist($suggestion2);

        $mealChoice2->setSuggestion($suggestion2);
        $manager->persist($mealChoice2);

        $mealChoice3 = new MealChoice();
        $mealChoice3->setMeal($mealA);
        $mealChoice3->setUser($userA);
        $manager->persist($mealChoice3);

        $mealChoice4 = new MealChoice();
        $mealChoice4->setMeal($mealA);
        $mealChoice4->setUser($userB);
        $manager->persist($mealChoice4);

        $suggestion3 = new GroupSuggestion();
        $suggestion3->setTimestamp(new \DateTime());
        $suggestion3->setSuggestions([$mealA]);
        $suggestion3->setMealChoices([$mealChoice3, $mealChoice4]);
        $suggestion3->setUsers([$userA, $userB]);
        $manager->persist($suggestion3);

        $mealChoice3->setSuggestion($suggestion3);
        $manager->persist($mealChoice3);

        $mealChoice4->setSuggestion($suggestion3);
        $manager->persist($mealChoice4);

        $manager->flush();
    }
}