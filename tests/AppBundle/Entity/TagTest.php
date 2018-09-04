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


use AppBundle\Entity\Extraction;
use AppBundle\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testTag() {
        $t = new Tag();
        $t->setId(55);
        $t->setValue("value");
        $p = new Tag();
        $t->setParent($p);
        $c = [new Tag()];
        $t->setChildren($c);
        $t->setPrint(true);
        $e = new Extraction();
        $t->setExtraction($e);
        $t->setType(Tag::TYPE_PRICE);
        $t->setFormat("format");

        $this->assertEquals(55, $t->getId());
        $this->assertEquals("value", $t->getValue());
        $this->assertEquals("value", $t->__toString());
        $this->assertEquals($p, $t->getParent());
        $this->assertEquals($c, $t->getChildren());
        $this->assertTrue($t->isPrint());
        $this->assertEquals($e, $t->getExtraction());
        $this->assertEquals(Tag::TYPE_PRICE, $t->getType());
        $this->assertEquals("format", $t->getFormat());
    }
}