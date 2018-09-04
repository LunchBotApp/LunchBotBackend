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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;

/**
 * Class MealControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class GroupControllerTest extends WebTestCase
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

        $groupFixture = new LoadGroup();
        $groupFixture->setContainer(self::$kernel->getContainer());
        $groupFixture->load($entityManager);
    }

    public function testGroupMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/groups/token/get', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testGroupInexistent()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/groups/token/get', ['group' => '11']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "This group doesn't exist");
    }

    public function testGroup()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/groups/token/get', ['group' => 'group']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["message"], "Success");
        $this->assertSame($responseArray["token"], "group");
    }

    public function testGroupAddMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/groups/add', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testGroupAdd()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/groups/add', ['group' => '1', 'token' => '1']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["message"], "New Group added");
    }
}