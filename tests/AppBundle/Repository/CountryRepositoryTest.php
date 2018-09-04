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

namespace Tests\AppBundle\Repository;

use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;
use AppBundle\Entity\Country;
use AppBundle\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;

class CountryRepositoryTest extends KernelTestCase
{
    private $em;
    private $repo;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime(self::$kernel);

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $fixture = new LoadRepositoryTestData();
        $fixture->load($this->em);

        $this->repo = $this->em->getRepository(Country::class);
    }

    public function testGetByName()
    {
        $country = $this->repo->getByName('Deutschland');
        $countryName = $country->getName();
        $cities = $country->getCities();
        $cityNames = [];
        foreach ($cities as $city) {
            $cityNames[] = $city->getName();
        }
        $expectedCityNames = ['Karlsruhe','Stuttgart'];

        sort($cityNames);
        sort($expectedCityNames);

        $this->assertEquals($expectedCityNames, $cityNames);
        $this->assertEquals('Deutschland', $countryName);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
