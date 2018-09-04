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

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use AppBundle\Entity\Language;
use AppBundle\Entity\PriceRange;
use AppBundle\Entity\Settings;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SettingsController
 *
 * @package AppBundle\Controller
 */
class SettingsController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="New priceRange",
     * )
     * @Post("/api/v1/settings/pricerange", defaults={"_format"="json"})
     * @param Request $request
     * @return array
     */
    public function setPriceRange(Request $request)
    {
        $userID = $request->request->get('user');
        $min    = $request->request->get('min');
        $max    = $request->request->get('max');

        if (!$userID || !$max) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        if (!is_numeric($min) || !is_numeric($max)) {
            return ["return_code" => 0, "message" => "Min and max need to be numbers", "user_created" => 0];
        }

        $user = $this->getDoctrine()->getRepository(User::class)->getByUserId($userID);
        $em   = $this->getDoctrine()->getManager();

        if ($user === null) {
            $user = new User();
            $user->setUserId($userID);
            $settings   = new Settings();
            $priceRange = new PriceRange();
            $priceRange->setMin($min);
            $priceRange->setMax($max);
            $settings->setPriceRange($priceRange);
            $user->setSettings($settings);

            $em->persist($priceRange);
            $em->persist($settings);
            $em->persist($user);
            $em->flush();

            return ["return_code" => 1, "message" => "New priceRange saved", "user_created" => 1];
        } else {
            $settings = $user->getSettings();

            if ($settings === null) {
                $settings   = new Settings();
                $priceRange = new PriceRange();
                $priceRange->setMin($min);
                $priceRange->setMax($max);
                $settings->setPriceRange($priceRange);
                $user->setSettings($settings);

                $em->persist($priceRange);
                $em->persist($settings);
                $em->merge($user);
                $em->flush();

                return ["return_code" => 1, "message" => "New priceRange saved", "user_created" => 0];
            } else {
                $priceRange = $settings->getPriceRange();

                if ($priceRange === null) {
                    $priceRange = new PriceRange();
                    $priceRange->setMin($min);
                    $priceRange->setMax($max);
                    $settings->setPriceRange($priceRange);
                    $user->setSettings($settings);

                    $em->persist($priceRange);
                    $em->merge($settings);
                    $em->merge($user);
                    $em->flush();

                    return ["return_code" => 1, "message" => "New priceRange saved", "user_created" => 0];
                } else {
                    $priceRange->setMin($min);
                    $priceRange->setMax($max);
                    $settings->setPriceRange($priceRange);
                    $user->setSettings($settings);

                    $em->merge($priceRange);
                    $em->merge($settings);
                    $em->merge($user);
                    $em->flush();

                    return ["return_code" => 1, "message" => "New priceRange saved", "user_created" => 0];
                }
            }
        }
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="New location",
     * )
     * @Post("/api/v1/settings/location", defaults={"_format"="json"})
     * @param Request $request
     * @return array
     */
    public function setLocation(Request $request)
    {
        $userID      = $request->request->get('user');
        $countryName = $request->request->get('country');
        $cityName    = $request->request->get('city');
        $street      = $request->request->get('street');
        $number      = $request->request->get('number');
        $code        = $request->request->get('code');

        if (!$userID || !$countryName || !$cityName || !$street || !$number || !$code) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        $user    = $this->getDoctrine()->getRepository(User::class)->getByUserId($userID);
        $country = $this->getDoctrine()->getRepository(Country::class)->getByName($countryName);
        $city    = $this->getDoctrine()->getRepository(City::class)->getByName($cityName);
        $em      = $this->getDoctrine()->getManager();

        if (is_null($country) || is_null($city)) {
            return ["return_code" => 0, "message" => "Stated country or city don't exist", "user_created" => 0];
        }

        if ($user === null) {
            $user = new User();
            $user->setUserId($userID);
            $settings = new Settings();
            $location = new Address();
            $location->setCountry($country);
            $location->setCity($city);
            $location->setStreet($street);
            $location->setNumber($number);
            $location->setCode($code);
            $settings->setLocation($location);
            $user->setSettings($settings);

            $em->persist($location);
            $em->persist($settings);
            $em->persist($user);
            $em->flush();

            return ["return_code" => 1, "message" => "New location saved", "user_created" => 1];
        } else {
            $settings = $user->getSettings();

            if ($settings === null) {
                $settings = new Settings();
                $location = new Address();
                $location->setCountry($country);
                $location->setCity($city);
                $location->setStreet($street);
                $location->setNumber($number);
                $location->setCode($code);
                $settings->setLocation($location);
                $user->setSettings($settings);

                $em->persist($location);
                $em->persist($settings);
                $em->merge($user);
                $em->flush();

                return ["return_code" => 1, "message" => "New location saved", "user_created" => 0];
            } else {
                $location = $settings->getLocation();

                if ($location === null) {
                    $location = new Address();
                    $location->setCountry($country);
                    $location->setCity($city);
                    $location->setStreet($street);
                    $location->setNumber($number);
                    $location->setCode($code);
                    $settings->setLocation($location);
                    $user->setSettings($settings);

                    $em->persist($location);
                    $em->merge($settings);
                    $em->merge($user);
                    $em->flush();

                    return ["return_code" => 1, "message" => "New location saved", "user_created" => 0];
                } else {
                    $location->setCountry($country);
                    $location->setCity($city);
                    $location->setStreet($street);
                    $location->setNumber($number);
                    $location->setCode($code);
                    $settings->setLocation($location);
                    $user->setSettings($settings);

                    $em->merge($location);
                    $em->merge($settings);
                    $em->merge($user);
                    $em->flush();

                    return ["return_code" => 1, "message" => "New location saved", "user_created" => 0];
                }
            }
        }
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="New distance",
     * )
     * @Post("/api/v1/settings/distance", defaults={"_format"="json"})
     * @param Request $request
     * @return array
     */
    public function setDistance(Request $request)
    {
        $userID   = $request->request->get('user');
        $distance = $request->request->get('distance');

        if (!$userID || !$distance) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        if (!is_numeric($distance)) {
            return ["return_code" => 0, "message" => "Distance needs to be a number", "user_created" => 0];
        }

        $user = $this->getDoctrine()->getRepository(User::class)->getByUserId($userID);
        $em   = $this->getDoctrine()->getManager();

        if ($user === null) {
            $user = new User();
            $user->setUserId($userID);
            $settings = new Settings();
            $settings->setDistance($distance);
            $user->setSettings($settings);

            $em->persist($settings);
            $em->persist($user);
            $em->flush();

            return ["return_code" => 1, "message" => "New distance saved", "user_created" => 1];
        } else {
            $settings = $user->getSettings();

            if ($settings === null) {
                $settings = new Settings();
                $settings->setDistance($distance);
                $user->setSettings($settings);

                $em->persist($settings);
                $em->merge($user);
                $em->flush();

                return ["return_code" => 1, "message" => "New distance saved", "user_created" => 0];
            } else {
                $settings->setDistance($distance);
                $user->setSettings($settings);

                $em->merge($settings);
                $em->merge($user);
                $em->flush();

                return ["return_code" => 1, "message" => "New distance saved", "user_created" => 0];
            }
        }
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="New language",
     * )
     * @Post("/api/v1/settings/language", defaults={"_format"="json"})
     * @param Request $request
     * @return array
     */
    public function setLanguage(Request $request)
    {
        $userID = $request->request->get('user');
        $locale = $request->request->get('language');

        if (!$userID || !$locale) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        $locales = $this->getParameter('languages');

        $user = $this->getDoctrine()->getRepository(User::class)->getByUserId($userID);
        $em   = $this->getDoctrine()->getManager();

        if (!in_array($locale, $locales)) {
            return ["return_code" => 0, "message" => "Stated locale doesn't exist", "user_created" => 0];
        }
        $language = $this->getDoctrine()->getRepository(Language::class)->getByLocale($locale);
        if (!$language) {
            $language = new Language();
            $language->setLocale($locale);
            $language->setName($locale);
            $em->persist($language);
            $em->flush();
        }

        if ($user === null) {
            $user = new User();
            $user->setUserId($userID);
            $settings = new Settings();
            $settings->setLanguage($language);
            $settings->setLocale($locale);
            $user->setSettings($settings);
            $em->persist($settings);
            $em->persist($user);
            $em->flush();

            return ["return_code" => 1, "message" => "New language saved", "user_created" => 1];
        } else {
            $settings = $user->getSettings();

            if ($settings === null) {
                $settings = new Settings();
                $settings->setLanguage($language);
                $settings->setLocale($locale);
                $user->setSettings($settings);
                $em->persist($settings);
                $em->merge($user);
                $em->flush();

                return ["return_code" => 1, "message" => "New language saved", "user_created" => 0];
            } else {
                $settings->setLocale($locale);
                $settings->setLanguage($language);
                $user->setSettings($settings);
                $em->merge($settings);
                $em->merge($user);
                $em->flush();

                return ["return_code" => 1, "message" => "New language saved", "user_created" => 0];
            }
        }
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Settings",
     * )
     * @Get("/api/v1/settings/{userid}/list", defaults={"_format"="json"})
     * @View(serializerGroups={"settings", "priceRange", "address", "language"})
     * @param Request $request
     * @return array
     */
    public function showSettings(Request $request, $userid)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->getByUserId($userid);
        $em   = $this->getDoctrine()->getManager();

        if (!$user) {
            $user = new User();
            $user->setUserId($userid);
            $settings = new Settings();
            $user->setSettings($settings);
            $em->persist($settings);
            $em->persist($user);
            $em->flush();

            return ["message" => "New User, no settings available", "user_created" => 1];
        }

        return ["settings" => $user->getSettings(), "user_created" => 0];
    }
}