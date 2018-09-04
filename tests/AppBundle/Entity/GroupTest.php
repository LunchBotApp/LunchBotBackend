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
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    public function test()
    {
        $user  = new User();
        $group = new Group("1","1");
        $group->setGroupId("1");
        $group->setUser([$user]);
        $group->setToken("1");
        $group->setId(1);


        $this->assertEquals(1, $group->getId());
        $this->assertEquals("1", $group->getGroupId());
        $this->assertEquals("1", $group->getToken());
        $this->assertEquals([$user], $group->getUser());
    }
}