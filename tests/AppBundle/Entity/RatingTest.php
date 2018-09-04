<?php


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