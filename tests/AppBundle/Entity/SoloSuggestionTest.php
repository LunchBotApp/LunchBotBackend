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


use AppBundle\Entity\Rating;
use AppBundle\Entity\Settings;
use AppBundle\Entity\SoloSuggestion;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class SoloSuggestionTest extends TestCase
{
    public function testSoloSuggestion() {
        $s = new SoloSuggestion();
        $r = new Rating();
        $s->addRating($r);
        $s->setId(62);
        $dt = new \DateTime();
        $s->setTimestamp($dt);
        $se = new Settings();
        $s->setSettings($se);
        $mc = [new Rating()];
        $s->setMealChoices($mc);
        $u = new User();
        $s->setUser($u);
        $su = [new SoloSuggestion()];
        $s->setSuggestions($su);

        $this->assertEquals([$r], $s->getRatings());
        $this->assertEquals(62, $s->getId());
        $this->assertEquals($dt, $s->getTimestamp());
        $this->assertEquals($se, $s->getSettings());
        $this->assertEquals($mc, $s->getMealChoices());
        $this->assertEquals($u, $s->getUser());
        $this->assertEquals($su, $s->getSuggestions());

        $s->setRatings($r);
        $this->assertEquals($r, $s->getRatings());
    }
}