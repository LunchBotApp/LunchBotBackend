<?php


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