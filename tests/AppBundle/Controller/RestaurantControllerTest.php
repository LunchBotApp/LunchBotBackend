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
class RestaurantControllerTest extends WebTestCase
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

    public function testList()
    {
        $client = $this->getClient('/restaurants');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Restaurants")')->count());
    }

    public function testAdd()
    {
        $client = $this->getClient('/restaurants/add');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Restaurant")')->count());
    }

    public function testEdit()
    {
        $client = $this->getClient('/restaurants/1/edit');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Restaurant")')->count());
    }

    public function testDetails()
    {
        $client = $this->getClient('/restaurants/1/details');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Oxford Café")')->count());
    }

    public function testAddValues()
    {
        $client = $this->getClient('/restaurants/add');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $form = $crawler->selectButton('Save')->form();

        // Set username and password
        $form['restaurant[name]']            = 'KIT RESTAURANT';
        $form['restaurant[address][number]'] = '5';
        $form['restaurant[address][street]'] = 'Am Fasanengarten';
        $form['restaurant[address][code]']   = '76131';
        $form['restaurant[website]']         = 'http://kit.edu';

        $client->submit($form);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Restaurant")')->count());
    }

    public function testAllApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/restaurants');
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame(count($responseArray), 5);
    }

    public function testDetailApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/restaurants/1');
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["id"], 1);
        $this->assertSame($responseArray["name"], "Oxford Café");
    }

    public function testDetailApiToday()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/restaurants/1', ['date'=>'today']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
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
}