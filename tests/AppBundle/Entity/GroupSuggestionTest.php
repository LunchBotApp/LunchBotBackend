<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\GroupSuggestion;
use AppBundle\Entity\Meal;
use AppBundle\Entity\MealChoice;
use AppBundle\Entity\Rating;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Settings;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class GroupSuggestionTest extends TestCase
{
    public function test()
    {
        $user       = new User();
        $restaurant = new Restaurant();
        $rating     = new Rating();
        $sett       = new Settings();
        $date       = new \DateTime();
        $meal       = new MealChoice();
        $m          = new Meal();
        $sugg       = new GroupSuggestion();
        $sugg->setId(1);
        $sugg->addUser($user);
        $sugg->addChosenMeal($m, $user);
        $sugg->setUsers([$user]);
        $sugg->setRestaurant($restaurant);
        $sugg->setSettings($sett);
        $sugg->setTimestamp($date);
        $sugg->setSuggestions([$sugg]);
        $sugg->setMealChoices([$meal]);
        $sugg->setRatings([$rating]);


        $this->assertEquals(1, $sugg->getId());
        $this->assertEquals($sett, $sugg->getSettings());
        $this->assertEquals([$user], $sugg->getUsers());
        $this->assertEquals([$sugg], $sugg->getSuggestions());
        $this->assertEquals($restaurant, $sugg->getRestaurant());
        $this->assertEquals($date, $sugg->getTimestamp());
        $this->assertEquals([$meal], $sugg->getMealChoices());
        $this->assertEquals([$rating], $sugg->getRatings());
    }

}