<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Meal;
use AppBundle\Entity\MealChoice;
use AppBundle\Entity\SoloSuggestion;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class MealChoiceTest extends TestCase
{
    public function test()
    {
        $user = new User();
        $m    = new Meal ();
        $sugg = new SoloSuggestion();
        $meal = new MealChoice();
        $meal->setId(1);
        $meal->setUser($user);
        $meal->setMeal($m);
        $meal->setSuggestion($sugg);


        $this->assertEquals(1, $meal->getId());
        $this->assertEquals($user, $meal->getUser());
        $this->assertEquals($m, $meal->getMeal());
        $this->assertEquals($sugg, $meal->getSuggestion());
    }
}