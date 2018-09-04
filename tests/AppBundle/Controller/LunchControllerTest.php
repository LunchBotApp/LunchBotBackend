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
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;
use AppBundle\DataFixtures\ORM\LoadSuggestion;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;

/**
 * Class MealControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class LunchControllerTest extends WebTestCase
{
    public function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);

        $client        = static::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $fixture = new LoadSuggestion();
        $fixture->setContainer(self::$kernel->getContainer());
        $fixture->load($entityManager);
    }

    public function testSoloMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/request/solo', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testSoloOne()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/request/solo', ['group' => 'groupId', 'user' => 'userIdA', 'settings' => ['pricerange' => '10-20', 'distance' => '3000']]);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertNotSame($responseArray["settings"], null);
    }

    public function testSolo2()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/request/solo', ['group' => 'groupId', 'user' => 'userIdA1', 'settings' => ['pricerange' => '10-20', 'distance' => '3000']]);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 500);
    }

    public function testSoloNoMeals()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/request/solo', ['group' => 'groupId', 'user' => 'userIdA', 'settings' => ['pricerange' => '10-20', 'distance' => '3000']]);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertNotSame($responseArray["settings"], null);
    }

    public function testGroupMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/request/group', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testGroupOne()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/request/group', ['group' => 'groupId', 'users' => 'userIdA;userIdB', 'settings' => ['pricerange' => '10-20', 'distance' => '3000']]);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertNotSame($responseArray["suggestion_id"], null);
    }

    public function testGetMealSetMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/meal/set', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testGetMealSet()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/meal/set', ['user' => 'userIdA', 'meal' => '1', 'suggestion' => '100']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
    }

    public function testRatingSetMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/rating/set', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testRatingSet()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/lunch/rating/set', ['value' => '5', 'meal_choice' => '1']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
    }

    /**
     * @param $url
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    public function getClient($url): \Symfony\Bundle\FrameworkBundle\Client
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', $url);

        $form = $crawler->selectButton('Log in')->form();

        // Set username and password
        $form['_username'] = 'admin';
        $form['_password'] = 'admin';

        $client->submit($form);

        return $client;
    }

    public function urlProvider()
    {
        return [
            ['/meals'],
        ];
    }
}