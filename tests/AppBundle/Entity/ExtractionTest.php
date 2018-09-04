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
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Tag;
use PHPUnit\Framework\TestCase;

class ExtractionTest extends TestCase
{

    public function test()
    {
        $restaurant = new Restaurant();
        $tag        = new Tag();
        $extraction = new Extraction();
        $extraction->setId(1);
        $extraction->setType(Extraction::TYPE_DOWNLOAD);
        $extraction->setRestaurant($restaurant);
        $extraction->setFileType(".pdf");
        $extraction->setKeyTerms(["1"]);
        $extraction->setRemotePass("1");
        $extraction->setRemoteUser("1");
        $extraction->setTag($tag);
        $extraction->setUrl("http://lunchbot.de");

        $this->assertEquals(1, $extraction->getId());
        $this->assertEquals($restaurant, $extraction->getRestaurant());
        $this->assertEquals(Extraction::TYPE_DOWNLOAD, $extraction->getType());
        $this->assertEquals(".pdf", $extraction->getFileType());
        $this->assertEquals(["1"], $extraction->getKeyTerms());
        $this->assertEquals("1", $extraction->getRemotePass());
        $this->assertEquals("1", $extraction->getRemoteUser());
        $this->assertEquals($tag, $extraction->getTag());
        $this->assertEquals("http://lunchbot.de", $extraction->getUrl());
    }

}