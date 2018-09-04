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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;

/**
 * Class MealControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class FeedbackReportControllerTest extends WebTestCase
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
    }


    public function testRestaurantsList()
    {
        $client = $this->getClient('/inbox/restaurant');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Requested Restaurants")')->count());
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

    public function testFeedbackList()
    {
        $client = $this->getClient('/inbox/feedback');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Feedback")')->count());
    }

    public function testFeedbackAddApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/feedback/add', ['user' => 'test', 'message' => 'test']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["message"], "Message saved");
    }

    public function testFeedbackMissingMessage()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/feedback/add', ['user' => 'test']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing Message");
    }

    public function testFeedbackMissingUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/feedback/add', ['message' => 'test']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing User");
    }

    public function testFeedbackMissingMessageUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/feedback/add', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing User and Message");
    }

    public function testRestaurantsAddApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/restaurants/request', ['user' => 'test', 'message' => 'test']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["message"], "Message saved");
    }

    public function testRestaurantsMissingMessage()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/restaurants/request', ['user' => 'test']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing Message");
    }

    public function testRestaurantsMissingUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/restaurants/request', ['message' => 'test']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing User");
    }

    public function testRestaurantsMissingMessageUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/restaurants/request', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing User and Message");
    }
}