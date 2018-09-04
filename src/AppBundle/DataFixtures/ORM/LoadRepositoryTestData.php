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

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Address;
use AppBundle\Entity\Category;
use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use AppBundle\Entity\CrawlerErrorReport;
use AppBundle\Entity\Extraction;
use AppBundle\Entity\FeedbackReport;
use AppBundle\Entity\Group;
use AppBundle\Entity\IssueReport;
use AppBundle\Entity\Language;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Message;
use AppBundle\Entity\PriceRange;
use AppBundle\Entity\Rating;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Settings;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Translation;
use AppBundle\Entity\User;
use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadRepositoryTestData
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadRepositoryTestData implements FixtureInterface, ContainerAwareInterface
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
        $country->setName('Deutschland');
        $manager->persist($country);

        $cityA =  new City();
        $cityA->setName('Karlsruhe');
        $cityA->setCountry($country);
        $manager->persist($cityA);

        $cityB =  new City();
        $cityB->setName('Stuttgart');
        $cityB->setCountry($country);
        $manager->persist($cityB);

        $country->setCities([$cityA, $cityB]);
        $manager->persist($country);

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

        $addressD = new Address();
        $addressD->setCountry($country);
        $addressD->setCity($cityB);
        $addressD->setStreet('Jklstraße');
        $addressD->setNumber(12);
        $addressD->setCode(70173);
        $manager->persist($addressD);

        $addressE = new Address();
        $addressE->setCountry($country);
        $addressE->setCity($cityA);
        $addressE->setStreet('Mnostraße');
        $addressE->setNumber(23);
        $addressE->setCode(70173);
        $manager->persist($addressE);

        $languageA = new Language();
        $languageA->setName('Deutsch');
        $languageA->setLocale('ger');
        $manager->persist($languageA);

        $languageB = new Language();
        $languageB->setName('English');
        $languageB->setLocale('eng');
        $manager->persist($languageB);

        $messageA = new Message();
        $messageA->setMessageKey('Welcome');
        $manager->persist($messageA);

        $messageB = new Message();
        $messageB->setMessageKey('Rating');
        $manager->persist($messageB);

        $priceRangeA = new PriceRange();
        $priceRangeA->setMin(1);
        $priceRangeA->setMax(5);
        $manager->persist($priceRangeA);

        $priceRangeB = new PriceRange();
        $priceRangeB->setMin(4);
        $priceRangeB->setMax(8);
        $manager->persist($priceRangeB);

        $translationA = new Translation();
        $translationA->setValue('Willkommen beim Lunchbot!');
        $translationA->setLanguage($languageA);
        $translationA->setMessage($messageA);
        $manager->persist($translationA);

        $translationB = new Translation();
        $translationB->setValue('Bitte bewerten sie ihr letztes Gericht.');
        $translationB->setLanguage($languageA);
        $translationB->setMessage($messageB);
        $manager->persist($translationB);

        $translationC = new Translation();
        $translationC->setValue('Welcome to the Lunchbot!');
        $translationC->setLanguage($languageB);
        $translationC->setMessage($messageA);
        $manager->persist($translationC);

        $translationD = new Translation();
        $translationD->setValue('Please rate your last meal.');
        $translationD->setLanguage($languageB);
        $translationD->setMessage($messageB);
        $manager->persist($translationD);

        $messageA->setTranslations([$translationA, $translationC]);
        $manager->persist($messageA);
        $messageB->setTranslations([$translationB, $translationD]);
        $manager->persist($messageB);

        $languageA->setTranslations([$translationA, $translationB]);
        $manager->persist($languageA);
        $languageB->setTranslations([$translationC, $translationD]);
        $manager->persist($languageB);

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

        $settingsD = new Settings();
        $manager->persist($settingsD);

        $settingsE = new Settings();
        $manager->persist($settingsE);

        $settingsF = new Settings();
        $manager->persist($settingsF);

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

        $userD = new User();
        $userD->setUserId('userIdD');
        $userD->setSettings($settingsD);
        $manager->persist($userD);

        $userE = new User();
        $userE->setUserId('userIdE');
        $userE->setSettings($settingsE);
        $manager->persist($userE);

        $userF = new User();
        $userF->setUserId('userIdF');
        $userF->setSettings($settingsF);
        $manager->persist($userF);

        $group = new Group('groupId', 'token');
        $group->setUser([$userA, $userB, $userC, $userD, $userE]);
        $manager->persist($group);

        $categoryA = new Category();
        $categoryA->setName('categoryA');
        $categoryA->addSearchTerm('catA');
        $manager->persist($categoryA);

        $categoryB = new Category();
        $categoryB->setName('categoryB');
        $categoryB->addSearchTerm('catB');
        $manager->persist($categoryB);

        $categoryC = new Category();
        $categoryC->setName('categoryC');
        $categoryC->addSearchTerm('catC');
        $manager->persist($categoryC);

        $extraction = new Extraction();
        $extraction->setUrl('restaurant/menu.de');
        $extraction->setType(Extraction::TYPE_WEB);
        $extraction->setRemoteUser('remoteUser');
        $extraction->setRemotePass('remotePass');
        $extraction->setKeyTerms(['keyterm']);
        $manager->persist($extraction);

        $restaurantA = new Restaurant();
        $restaurantA->setName('RestaurantA');
        $restaurantA->setWebsite('restaurant.de');
        $restaurantA->setPhone('0123456789');
        $restaurantA->setEmail('restaurant@email.de');
        $restaurantA->setAddress($addressA);
        $manager->persist($restaurantA);

        $restaurantB = new Restaurant();
        $restaurantB->setName('RestaurantB');
        $restaurantB->setAddress($addressB);
        $manager->persist($restaurantB);

        $restaurantC = new Restaurant();
        $restaurantC->setName('RestaurantC');
        $restaurantC->setAddress($addressC);
        $manager->persist($restaurantC);

        $restaurantD = new Restaurant();
        $restaurantD->setName('RestaurantD');
        $restaurantD->setAddress($addressD);
        $manager->persist($restaurantD);

        $restaurantE = new Restaurant();
        $restaurantE->setName('RestaurantE');
        $restaurantE->setAddress($addressE);
        $manager->persist($restaurantE);

        $mealA = new Meal();
        $mealA->setName('mealA');
        $mealA->setDate(new DateTime());
        $mealA->setCategories([$categoryA]);
        $mealA->setPrice(1);
        $mealA->setRestaurant($restaurantA);
        $manager->persist($mealA);

        $mealB = new Meal();
        $mealB->setName('mealB');
        $mealB->setDate(new DateTime());
        $mealB->setCategories([$categoryB]);
        $mealB->setPrice(2);
        $mealB->setRestaurant($restaurantA);
        $manager->persist($mealB);

        $mealC = new Meal();
        $mealC->setName('mealC');
        $mealC->setDate(new DateTime());
        $mealC->setCategories([$categoryA]);
        $mealC->setPrice(3);
        $mealC->setRestaurant($restaurantB);
        $manager->persist($mealC);

        $mealD = new Meal();
        $mealD->setName('mealD');
        $mealD->setDate(new DateTime());
        $mealD->setCategories([$categoryB]);
        $mealD->setPrice(4);
        $mealD->setRestaurant($restaurantB);
        $manager->persist($mealD);

        $mealE = new Meal();
        $mealE->setName('mealE');
        $mealE->setDate(new DateTime());
        $mealE->setCategories([$categoryA]);
        $mealE->setPrice(5);
        $mealE->setRestaurant($restaurantC);
        $manager->persist($mealE);

        $mealF = new Meal();
        $mealF->setName('mealF');
        $mealF->setDate(new DateTime());
        $mealF->setCategories([$categoryB]);
        $mealF->setPrice(6);
        $mealF->setRestaurant($restaurantC);
        $manager->persist($mealF);

        $mealG = new Meal();
        $mealG->setName('mealG');
        $mealG->setDate(new DateTime());
        $mealG->setCategories([$categoryA]);
        $mealG->setPrice(7);
        $mealG->setRestaurant($restaurantD);
        $manager->persist($mealG);

        $mealH = new Meal();
        $mealH->setName('mealH');
        $mealH->setDate(new DateTime());
        $mealH->setCategories([$categoryB]);
        $mealH->setPrice(8);
        $mealH->setRestaurant($restaurantD);
        $manager->persist($mealH);

        $mealI = new Meal();
        $mealI->setName('mealI');
        $mealI->setDate(new DateTime());
        $mealI->setCategories([$categoryA]);
        $mealI->setPrice(9);
        $mealI->setRestaurant($restaurantE);
        $manager->persist($mealI);

        $mealJ = new Meal();
        $mealJ->setName('mealJ');
        $mealJ->setDate(new DateTime());
        $mealJ->setCategories([$categoryB]);
        $mealJ->setPrice(10);
        $mealJ->setRestaurant($restaurantE);
        $manager->persist($mealJ);

        $mealK = new Meal();
        $mealK->setName('mealKcatA');
        $mealK->setDate(new DateTime());
        $mealK->setPrice(1000);
        $mealK->setRestaurant($restaurantE);
        $manager->persist($mealK);

        $ratingA = new Rating();
        $ratingA->setUser($userA);
        $ratingA->setMeal($mealA);
        $ratingA->setValue(1);
        $ratingA->setTimestamp(new DateTime());
        $manager->persist($ratingA);

        $ratingB = new Rating();
        $ratingB->setUser($userA);
        $ratingB->setMeal($mealB);
        $ratingB->setValue(2);
        $ratingB->setTimestamp(new DateTime());
        $manager->persist($ratingB);

        $ratingC = new Rating();
        $ratingC->setUser($userA);
        $ratingC->setMeal($mealC);
        $ratingC->setValue(3);
        $ratingC->setTimestamp(new DateTime());
        $manager->persist($ratingC);

        $ratingD = new Rating();
        $ratingD->setUser($userA);
        $ratingD->setMeal($mealD);
        $ratingD->setValue(4);
        $ratingD->setTimestamp(new DateTime());
        $manager->persist($ratingD);

        $ratingE = new Rating();
        $ratingE->setUser($userA);
        $ratingE->setMeal($mealE);
        $ratingE->setValue(5);
        $ratingE->setTimestamp(new DateTime());
        $manager->persist($ratingE);

        $ratingF = new Rating();
        $ratingF->setUser($userA);
        $ratingF->setMeal($mealF);
        $ratingF->setValue(3);
        $ratingF->setTimestamp(new DateTime());
        $manager->persist($ratingF);

        $ratingG = new Rating();
        $ratingG->setUser($userA);
        $ratingG->setMeal($mealG);
        $ratingG->setValue(1);
        $ratingG->setTimestamp(new DateTime());
        $manager->persist($ratingG);

        $ratingH = new Rating();
        $ratingH->setUser($userA);
        $ratingH->setMeal($mealH);
        $ratingH->setValue(4);
        $ratingH->setTimestamp(new DateTime());
        $manager->persist($ratingH);

        $ratingI = new Rating();
        $ratingI->setUser($userB);
        $ratingI->setMeal($mealA);
        $ratingI->setValue(2);
        $ratingI->setTimestamp(new DateTime());
        $manager->persist($ratingI);

        $ratingJ = new Rating();
        $ratingJ->setUser($userB);
        $ratingJ->setMeal($mealB);
        $ratingJ->setValue(1);
        $ratingJ->setTimestamp(new DateTime());
        $manager->persist($ratingJ);

        $ratingK = new Rating();
        $ratingK->setUser($userB);
        $ratingK->setMeal($mealC);
        $ratingK->setValue(2);
        $ratingK->setTimestamp(new DateTime());
        $manager->persist($ratingK);

        $ratingL = new Rating();
        $ratingL->setUser($userB);
        $ratingL->setMeal($mealD);
        $ratingL->setValue(4);
        $ratingL->setTimestamp(new DateTime());
        $manager->persist($ratingL);

        $ratingM = new Rating();
        $ratingM->setUser($userB);
        $ratingM->setMeal($mealE);
        $ratingM->setValue(1);
        $ratingM->setTimestamp(new DateTime());
        $manager->persist($ratingM);

        $ratingN = new Rating();
        $ratingN->setUser($userB);
        $ratingN->setMeal($mealF);
        $ratingN->setValue(5);
        $ratingN->setTimestamp(new DateTime());
        $manager->persist($ratingN);

        $ratingO = new Rating();
        $ratingO->setUser($userB);
        $ratingO->setMeal($mealG);
        $ratingO->setValue(3);
        $ratingO->setTimestamp(new DateTime());
        $manager->persist($ratingO);

        $ratingP = new Rating();
        $ratingP->setUser($userB);
        $ratingP->setMeal($mealH);
        $ratingP->setValue(4);
        $ratingP->setTimestamp(new DateTime());
        $manager->persist($ratingP);

        $ratingQ = new Rating();
        $ratingQ->setUser($userC);
        $ratingQ->setMeal($mealA);
        $ratingQ->setValue(1);
        $ratingQ->setTimestamp(new DateTime());
        $manager->persist($ratingQ);

        $ratingR = new Rating();
        $ratingR->setUser($userC);
        $ratingR->setMeal($mealB);
        $ratingR->setValue(2);
        $ratingR->setTimestamp(new DateTime());
        $manager->persist($ratingR);

        $ratingS = new Rating();
        $ratingS->setUser($userC);
        $ratingS->setMeal($mealC);
        $ratingS->setValue(3);
        $ratingS->setTimestamp(new DateTime());
        $manager->persist($ratingS);

        $ratingT = new Rating();
        $ratingT->setUser($userC);
        $ratingT->setMeal($mealD);
        $ratingT->setValue(4);
        $ratingT->setTimestamp(new DateTime());
        $manager->persist($ratingT);

        $ratingU = new Rating();
        $ratingU->setUser($userC);
        $ratingU->setMeal($mealE);
        $ratingU->setValue(5);
        $ratingU->setTimestamp(new DateTime());
        $manager->persist($ratingU);

        $ratingV = new Rating();
        $ratingV->setUser($userC);
        $ratingV->setMeal($mealF);
        $ratingV->setValue(3);
        $ratingV->setTimestamp(new DateTime());
        $manager->persist($ratingV);

        $ratingW = new Rating();
        $ratingW->setUser($userC);
        $ratingW->setMeal($mealG);
        $ratingW->setValue(1);
        $ratingW->setTimestamp(new DateTime());
        $manager->persist($ratingW);

        $ratingX = new Rating();
        $ratingX->setUser($userC);
        $ratingX->setMeal($mealH);
        $ratingX->setValue(4);
        $ratingX->setTimestamp(new DateTime());
        $manager->persist($ratingX);

        $ratingY = new Rating();
        $ratingY->setUser($userD);
        $ratingY->setMeal($mealA);
        $ratingY->setValue(2);
        $ratingY->setTimestamp(new DateTime());
        $manager->persist($ratingY);

        $ratingZ = new Rating();
        $ratingZ->setUser($userD);
        $ratingZ->setMeal($mealB);
        $ratingZ->setValue(1);
        $ratingZ->setTimestamp(new DateTime());
        $manager->persist($ratingZ);

        $ratingAB = new Rating();
        $ratingAB->setUser($userD);
        $ratingAB->setMeal($mealC);
        $ratingAB->setValue(2);
        $ratingAB->setTimestamp(new DateTime());
        $manager->persist($ratingAB);

        $ratingAB = new Rating();
        $ratingAB->setUser($userD);
        $ratingAB->setMeal($mealD);
        $ratingAB->setValue(4);
        $ratingAB->setTimestamp(new DateTime());
        $manager->persist($ratingAB);

        $ratingAC = new Rating();
        $ratingAC->setUser($userD);
        $ratingAC->setMeal($mealE);
        $ratingAC->setValue(1);
        $ratingAC->setTimestamp(new DateTime());
        $manager->persist($ratingAC);

        $ratingAD = new Rating();
        $ratingAD->setUser($userD);
        $ratingAD->setMeal($mealF);
        $ratingAD->setValue(5);
        $ratingAD->setTimestamp(new DateTime());
        $manager->persist($ratingAD);

        $ratingAE = new Rating();
        $ratingAE->setUser($userD);
        $ratingAE->setMeal($mealG);
        $ratingAE->setValue(3);
        $ratingAE->setTimestamp(new DateTime());
        $manager->persist($ratingAE);

        $ratingAF = new Rating();
        $ratingAF->setUser($userD);
        $ratingAF->setMeal($mealH);
        $ratingAF->setValue(4);
        $ratingAF->setTimestamp(new DateTime());
        $manager->persist($ratingAF);

        $tag = new Tag();
        $tag->setValue('value');
        $tag->setPrint(false);
        $tag->setType(Tag::TYPE_DESCR);
        $tag->setExtraction($extraction);
        $manager->persist($tag);

        $extraction->setTag($tag);
        $manager->persist($extraction);

        $reportA = new CrawlerErrorReport();
        $reportA->setUser($userA);
        $reportA->setErrorMessage('The crawler is not working correctly');
        $reportA->setMessage('The crawler is not working correctly');
        $reportA->setDate(new \DateTime());
        $manager->persist($reportA);

        $reportB = new FeedbackReport();
        $reportB->setDate(new \DateTime());
        $reportB->setMessage('I love LunchBot!');
        $reportB->setUser($userB);
        $reportB->setFeedbackType('default');
        $manager->persist($reportB);

        $reportC = new FeedbackReport();
        $reportC->setDate(new \DateTime());
        $reportC->setMessage('XY would be a nice restaurant to add!');
        $reportC->setUser($userC);
        $reportC->setFeedbackType('restaurant');
        $manager->persist($reportC);

        $reportD = new IssueReport();
        $reportD->setDate(new \DateTime());
        $reportD->setMessage('There is a bug!');
        $reportD->setUser($userD);
        $manager->persist($reportD);

        $manager->flush();
    }
}