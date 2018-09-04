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


use AppBundle\Entity\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function test()
    {
        $message = new Message();
        $message->setId(1);
        $message->setMessageKey("1");
        $message->setTranslations(["1"]);

        $this->assertEquals(1, $message->getId());
        $this->assertEquals("1", $message->getMessageKey());
        $this->assertEquals(["1"], $message->getTranslations());
    }
}