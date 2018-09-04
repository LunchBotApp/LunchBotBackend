<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Address;
use AppBundle\Entity\AdminUser;
use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use AppBundle\Entity\Extraction;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Tag;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadData implements FixtureInterface, ContainerAwareInterface
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

        $user = new AdminUser();
        $user->setUsername("admin");
        $user->setPlainPassword("admin");
        $user->setEmail("admin@lunchbot.de");
        $user->setName("admin");
        $user->setEnabled(true);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();

        $restaurants       = ["Oxford Café", "Mensa Am Adenauerring", "Mensa Moltke", "Mensa Erzberger", "Mensa Gottesaue", "Mensa Tiefenbronner", "Mensa Holzgarten", "Gasthaus Marktlücke", "Restaurant Sokrates", "Alte Bank", "Bratar", "Fabrik"];
        $restaurantDetails = [

            "Oxford Café"            => [
                "type"    => Extraction::TYPE_WEB,
                "key"     => "adenauerring",
                "website" => "http://oxford-cafe.de",
                "phone"   => "004972135482250",
                "email"   => "oxfordcafe@outlook.de",
                "street"  => "Kaiserstraße",
                "number"  => "57",
                "zip"     => "76133",
                "city"    => "Karlsruhe"
            ],
            "Mensa Am Adenauerring"  => [
                "type"    => Extraction::TYPE_API,
                "key"     => "adenauerring",
                "website" => "http://sw-ka.de",
                "phone"   => "00497216909230",
                "email"   => "gastronomie@sw-ka.de",
                "street"  => "Adenauerring",
                "number"  => "7",
                "zip"     => "76131",
                "city"    => "Karlsruhe"
            ], "Mensa Moltke"        => [
                "type"    => Extraction::TYPE_API,
                "key"     => "moltke",
                "website" => "http://sw-ka.de",
                "phone"   => "00497216909257",
                "email"   => "gastronomie@sw-ka.de",
                "street"  => "Moltkestraße",
                "number"  => "7",
                "zip"     => "76131",
                "city"    => "Karlsruhe"
            ], "Mensa Erzberger"     => [
                "type"    => Extraction::TYPE_API,
                "key"     => "erzberger",
                "website" => "http://sw-ka.de",
                "phone"   => "0049721752380",
                "email"   => "gastronomie@sw-ka.de",
                "street"  => "Erzbergerstraße",
                "number"  => "121",
                "zip"     => "76149",
                "city"    => "Karlsruhe"
            ], "Mensa Gottesaue"     => [
                "type"    => Extraction::TYPE_API,
                "key"     => "gottesaue",
                "website" => "http://sw-ka.de",
                "phone"   => "00497216629242",
                "email"   => "gastronomie@sw-ka.de",
                "street"  => "Am Schloss Gottesaue",
                "number"  => "7a",
                "zip"     => "76131",
                "city"    => "Karlsruhe"
            ], "Mensa Tiefenbronner" => [
                "type"    => Extraction::TYPE_API,
                "key"     => "tiefenbronner",
                "website" => "http://sw-ka.de",
                "phone"   => "004972317816220",
                "email"   => "gastronomie@sw-ka.de",
                "street"  => "Tiefenbronner-Straße",
                "number"  => "65",
                "zip"     => "75175",
                "city"    => "Pforzheim"
            ], "Mensa Holzgarten"    => [
                "type"    => Extraction::TYPE_API,
                "key"     => "holzgarten",
                "website" => "http://sw-ka.de",
                "phone"   => "00497231286796",
                "email"   => "gastronomie@sw-ka.de",
                "street"  => "Holzgartenstraße",
                "number"  => "36",
                "zip"     => "75175",
                "city"    => "Pforzheim"
            ]
            , "Gasthaus Marktlücke"  => [
                "key"      => "?",
                "type"     => Extraction::TYPE_DOWNLOAD,
                "fileType" => ".pdf",
                "keyTerms" => ['Mittagsmenüs', 'Zu allen Essen', '€'],
                "url"      => "http://server.tschatten.de/marktluecke/images/stories/pdf/mittagstisch.pdf",
                "website"  => "http://www.karlsruhermarktluecke.de",
                "phone"    => "00497216699829",
                "email"    => "info@karlsruhermarktluecke.de",
                "street"   => "Zähringerstr.",
                "number"   => "96",
                "zip"      => "76133",
                "city"     => "Karlsruhe"
            ]
            , "Restaurant Sokrates"  => [
                "key"      => "?",
                "type"     => Extraction::TYPE_DOWNLOAD,
                "fileType" => ".pdf",
                "keyTerms" => ['M I T T A G S K A R T E  	 (gültig Di-Fr von 11.30-15.00 Uhr)', '* alle Gerichte werden mit Salat serviert', '€'],
                "url"      => "https://www.sokrates-karlsruhe.de/data/mittagskarte_201611.pdf",
                "website"  => "https://www.sokrates-karlsruhe.de",
                "phone"    => "00490721813181",
                "email"    => "info@sokrates-karlsruhe.de",
                "street"   => "Welfenstraße",
                "number"   => "18",
                "zip"      => "76137",
                "city"     => "Karlsruhe"
            ]
            ,  "Alte Bank"            => [
                "key"      => "?",
                "type"     => Extraction::TYPE_DOWNLOAD,
                "fileType" => ".pdf",
                "keyTerms" => [],
                "url"      => "https://altebank.de/wp-content/uploads/2018/07/Alte-Bank-Wochenkarte-09.07.-14.07.18.pdf",
                "website"  => "https://altebank.de",
                "phone"    => "00497211832818",
                "email"    => "/",
                "street"   => "Herrenstr.",
                "number"   => "30",
                "zip"      => "76133",
                "city"     => "Karlsruhe"
            ]
            , "Bratar"               => [
                "key"      => "?",
                "type"     => Extraction::TYPE_DOWNLOAD,
                "fileType" => ".pdf",
                "keyTerms" => ['MittagsMo - Fr von 11-16hTisch', 'Yo u r   l o v e', '0'],
                "url"      => "http://bratar.de/karten/erbprinzenstr/mittageps.pdf",
                "website"  => "http://bratar.de",
                "phone"    => "0049072198230230",
                "email"    => "info@bratar.de",
                "street"   => "Erbprinzenstrasse",
                "number"   => "27",
                "zip"      => "76133",
                "city"     => "Karlsruhe"
            ]
            , "Fabrik"               => [
                "key"      => "?",
                "type"     => Extraction::TYPE_DOWNLOAD,
                "fileType" => ".jpg",
                "keyTerms" => ['\n', '10.5', '2;3;5;7;9'],
                "url"      => "https://www.fabrik.lu/wp-content/uploads/mdls/Fabrik---mdls.jpg",
                "website"  => "https://www.fabrik.lu",
                "phone"    => "0035227403333",
                "email"    => "info@bratar.de",
                "street"   => "Rue de la Gare",
                "number"   => "33",
                "zip"      => "7535",
                "city"     => "Mersch"
            ]

        ];
        for ($i = 0; $i < count($restaurants); $i++) {
            $country = $manager->getRepository('AppBundle:Country')->findOneBy(['name' => 'Germany']);
            if (empty($country)) {
                $country = new Country();
                $country->setName('Germany');
                $manager->persist($country);
                $manager->flush();

                $country2 = new Country();
                $country2->setName('Luxembourg');
                $manager->persist($country2);
                $manager->flush();
                $city = new City();
                $city->setName("Luxembourg");
                $city->setCountry($country2);
                $manager->persist($city);
                $manager->flush();
            }
            $city = $manager->getRepository('AppBundle:City')->findOneBy(['name' => $restaurantDetails[$restaurants[$i]]['city']]);
            if (empty($city)) {
                $city = new City();
                $city->setName($restaurantDetails[$restaurants[$i]]['city']);
                $city->setCountry($country);
                $manager->persist($city);
                $manager->flush();


            }
            $address = new Address();
            $address->setNumber($restaurantDetails[$restaurants[$i]]['number']);
            $address->setStreet($restaurantDetails[$restaurants[$i]]['street']);
            $address->setCode($restaurantDetails[$restaurants[$i]]['zip']);
            $address->setCountry($country);
            $address->setCity($city);
            $manager->persist($address);
            $manager->flush();

            $extraction = new Extraction();

            if ($restaurantDetails[$restaurants[$i]]['type'] == Extraction::TYPE_API) {
                $extraction->setType(Extraction::TYPE_API);
                $extraction->setUrl("");
                $extraction->setRemoteUser("1");
                $extraction->setRemotePass("1");

                $tag = new Tag();
                $tag->setValue($restaurantDetails[$restaurants[$i]]['key']);
                $tag->setPrint(false);
                $tag->setExtraction($extraction);
                $manager->persist($tag);
                $manager->flush();
            } elseif ($restaurantDetails[$restaurants[$i]]['type'] == Extraction::TYPE_WEB) {
                $extraction->setType(Extraction::TYPE_WEB);
                $extraction->setUrl("https://us.lunchbot.de/tests/oxford.html");
                $tag = new Tag();
                $tag->setValue(".panel");
                $tag->setPrint(false);
                $tag->setExtraction($extraction);
                $manager->persist($tag);
                $manager->flush();

                $tag2 = new Tag();
                $tag2->setValue(".panel-title");
                $tag2->setType(Tag::TYPE_DATE);
                $tag2->setFormat('d.m.Y');
                $tag2->setPrint(true);
                $tag2->setParent($tag);
                $manager->persist($tag2);
                $manager->flush();

                $tag3 = new Tag();
                $tag3->setValue(".fooditem");
                $tag3->setPrint(false);
                $tag3->setParent($tag);
                $manager->persist($tag3);
                $manager->flush();

                $tag4 = new Tag();
                $tag4->setValue(".fooditem_title");
                $tag4->setType(Tag::TYPE_DESCR);
                $tag4->setPrint(true);
                $tag4->setParent($tag3);
                $manager->persist($tag4);
                $manager->flush();

                $tag5 = new Tag();
                $tag5->setValue(".fooditem_price");
                $tag5->setType(Tag::TYPE_PRICE);
                $tag5->setPrint(true);
                $tag5->setParent($tag3);
                $manager->persist($tag5);
                $manager->flush();
            } else {

                $extraction->setUrl($restaurantDetails[$restaurants[$i]]['url']);
                $extraction->setFileType($restaurantDetails[$restaurants[$i]]['fileType']);
                $extraction->setKeyTerms($restaurantDetails[$restaurants[$i]]['keyTerms']);
            }
            $extraction->setType($restaurantDetails[$restaurants[$i]]['type']);
            $manager->persist($extraction);
            $manager->flush();

            $restaurant = new Restaurant();
            $restaurant->setName($restaurants[$i]);
            $restaurant->setAddress($address);
            $restaurant->setWebsite($restaurantDetails[$restaurants[$i]]['website']);
            $restaurant->setPhone($restaurantDetails[$restaurants[$i]]['phone']);
            $restaurant->setEmail($restaurantDetails[$restaurants[$i]]['email']);
            $restaurant->setExtraction($extraction);

            $manager->persist($restaurant);
            $manager->flush();
        }
    }
}