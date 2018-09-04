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
 * Time: 13:57
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\AdminUser;
use PHPUnit\Framework\TestCase;

class AdminUserTest extends TestCase
{

    public function testGetName()
    {
        $au = new AdminUser();
        $au->setName("Name");
        $this->assertEquals("Name", $au->getName());
    }

    public function testGetId()
    {
        $au = new AdminUser();
        $au->setId(42);
        $this->assertEquals(42, $au->getId());
    }
}
