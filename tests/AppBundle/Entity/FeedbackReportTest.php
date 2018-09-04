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


use AppBundle\Entity\FeedbackReport;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class FeedbackReportTest extends TestCase
{
    public function test()
    {
        $user = new User();
        $user->setUserId("1");
        $date   = new \DateTime();
        $cError = new FeedbackReport();
        $cError->setId(1);
        $cError->setMessage("Hello");
        $cError->setUser($user);
        $cError->setDate($date);
        $cError->setFeedbackType(FeedbackReport::TYPE_DEFAULT);
        $cError->setFinished(false);

        $this->assertEquals(1, $cError->getId());
        $this->assertEquals("Hello", $cError->getMessage());
        $this->assertEquals($user, $cError->getUser());
        $this->assertEquals($date, $cError->getDate());
        $this->assertEquals(false, $cError->getFinished());
        $this->assertEquals(FeedbackReport::TYPE_DEFAULT, $cError->getFeedbackType());
    }

}