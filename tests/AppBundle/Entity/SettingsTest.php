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


use AppBundle\Entity\Address;
use AppBundle\Entity\Language;
use AppBundle\Entity\PriceRange;
use AppBundle\Entity\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    public function testSettings() {
        $s = new Settings();
        $s->setId(11);
        $pr = new PriceRange();
        $s->setPriceRange($pr);
        $a = new Address();
        $s->setLocation($a);
        $s->setDistance(20);
        $l = new Language();
        $s->setLanguage($l);
        $s->setLocale("de");

        $this->assertEquals(11, $s->getId());
        $this->assertEquals($pr, $s->getPriceRange());
        $this->assertEquals($a, $s->getLocation());
        $this->assertEquals(20, $s->getDistance());
        $this->assertEquals($l, $s->getLanguage());
        $this->assertEquals("de", $s->getLocale());
    }
}