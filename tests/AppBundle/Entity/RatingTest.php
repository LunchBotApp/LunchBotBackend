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


use AppBundle\Entity\Meal;
use AppBundle\Entity\Rating;
use AppBundle\Entity\SoloSuggestion;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{
    public function test()
    {
        $user   = new User();
        $date   = new \DateTime();
        $meal   = new Meal();
        $sugg   = new SoloSuggestion();
        $rating = new Rating();
        $rating->setId(1);
        $rating->setSuggestion($sugg);
        $rating->setTimestamp($date);
        $rating->setMeal($meal);
        $rating->setValue(1);
        $rating->setUser($user);

        $this->assertEquals(1, $rating->getId());
        $this->assertEquals($sugg, $rating->getSuggestion());
        $this->assertEquals($date, $rating->getTimestamp());
        $this->assertEquals($meal, $rating->getMeal());
        $this->assertEquals(1, $rating->getValue());
        $this->assertEquals($user, $rating->getUser());
    }
}