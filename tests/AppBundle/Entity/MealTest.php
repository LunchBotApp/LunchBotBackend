<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Restaurant;
use PHPUnit\Framework\TestCase;

class MealTest extends TestCase
{
    public function test()
    {
        $date       = new \DateTime();
        $restaurant = new Restaurant();
        $restaurant->setName("1");
        $meal = new Meal();
        $cat  = new Category();

        $meal->setId(1);
        $meal->addCategory($cat);
        $meal->setName("1");
        $meal->setRestaurant($restaurant);
        $meal->setDate($date);
        $meal->setPrice(1.1);
        $meal->setAddition("1");
        $meal->setPlace("1");
        $meal->setCategories([$cat]);

        $this->assertEquals("1_1", $meal);
        $this->assertEquals(1, $meal->getId());
        $this->assertEquals("1", $meal->getName());
        $this->assertEquals($restaurant, $meal->getRestaurant());
        $this->assertEquals($date, $meal->getDate());
        $this->assertEquals(1.1, $meal->getPrice());
        $this->assertEquals("1", $meal->getPlace());
        $this->assertEquals("1", $meal->getAddition());
        $this->assertEquals([$cat], $meal->getCategories());
    }
}