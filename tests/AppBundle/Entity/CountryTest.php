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

/**
 * Created by PhpStorm.
 * User: ladmin
 * Date: 27.08.18
 * Time: 14:05
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{

    public function testGetId()
    {
        $c = new Country();
        $c->setId(98);
        $this->assertEquals(98, $c->getId());
    }

    public function testGetCities()
    {
        $c = new Country();
        $cs = [new City()];
        $c->setCities($cs);
        $this->assertEquals($cs, $c->getCities());
    }

    public function testGetName()
    {
        $c = new Country();
        $c->setName("Land");
        $this->assertEquals("Land", $c->getName());
        $this->assertEquals("Land", $c->__toString());
    }
}
