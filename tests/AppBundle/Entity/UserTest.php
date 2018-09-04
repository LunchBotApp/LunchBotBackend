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

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Group;
use AppBundle\Entity\Settings;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser() {
        $u = new User();
        $u->setId(55);
        $u->setUserId("ABCD45");
        $g = new Group("13", "27");
        $u->setGroup($g);
        $s = new Settings();
        $u->setSettings($s);

        $this->assertEquals(55, $u->getId());
        $this->assertEquals("ABCD45", $u->getUserId());
        $this->assertEquals("ABCD45", $u->__toString());
        $this->assertEquals($g, $u->getGroup());
        $this->assertEquals($s, $u->getSettings());
    }
}