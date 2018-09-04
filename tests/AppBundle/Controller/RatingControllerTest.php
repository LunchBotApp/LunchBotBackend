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
use AppBundle\DataFixtures\ORM\LoadGroup;
use AppBundle\DataFixtures\ORM\LoadRating;
use AppBundle\DataFixtures\ORM\LoadRatingController;
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;

/**
 * Class MealControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
  class RatingControllerTest extends WebTestCase
{
    public function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);

        $client        = static::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $em            = $doctrine->getManager();

        $ratingFixture = new LoadRatingController();
        $ratingFixture->setContainer(self::$kernel->getContainer());
        $ratingFixture->load($em);
    }

    public function testRatingMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/ratings/add', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }


    public function testRating()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/ratings/add', ['user' => 'user', 'suggestion' => '1', 'value' => '5']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }



    public function testRatingUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/ratings/add', ['user' => 'userIdA', 'suggestion' => '1', 'value' => '5']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["message"], "Rating saved");
    }

    public function testNoMealSelected()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/ratings/add', ['user' => 'userIdB', 'suggestion' => '2', 'value' => '5']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "The user didn't select any meal");
    }

    public function testGroup()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/ratings/add', ['user' => 'userIdB', 'suggestion' => '3', 'value' => '5']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["message"], "Rating saved");
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