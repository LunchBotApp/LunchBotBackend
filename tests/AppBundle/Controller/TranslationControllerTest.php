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

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadData;
use AppBundle\Entity\Language;
use AppBundle\Entity\Settings;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;

/**
 * Class MealControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class TranslationControllerTest extends WebTestCase
{
    public function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);

        $client        = static::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $fixture = new LoadData();
        $fixture->load($entityManager);

        $user = new User();
        $user->setUserId('user');
        $settings = new Settings();
        $settings->setLocale('en');
        $language = $doctrine->getRepository(Language::class)->getByLocale('en');
        if (!$language) {
            $language = new Language();
            $language->setLocale('en');
            $language->setName('en');
            $entityManager->persist($language);
            $entityManager->flush();
        }
        $settings->setLanguage($language);
        $entityManager->persist($settings);
        $entityManager->flush();
        $user->setSettings($settings);
        $entityManager->persist($user);
        $entityManager->flush();
    }


    public function testTranslationMessage()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/translation', ['message' => 'slack.welcome']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testTranslationMessageUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/translation', ['message' => 'slack.welcome', 'user' => 'user']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testLocales()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/locales', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
    }
}