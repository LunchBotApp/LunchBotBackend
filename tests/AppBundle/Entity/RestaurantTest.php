<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Address;
use AppBundle\Entity\Extraction;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Restaurant;
use PHPUnit\Framework\TestCase;

class RestaurantTest extends TestCase
{
    public function testRestaurant() {
        $r = new Restaurant();
        $m = new Meal();
        $r->addMeal($m);
        $r->setName("restaurant");
        $r->setId(9);
        $r->setWebsite("http://website.local");
        $r->setPhone("0123456789");
        $r->setEmail("info@website.local");
        $a = new Address();
        $r->setAddress($a);
        $e = new Extraction();
        $r->setExtraction($e);

        $this->assertEquals([$m], $r->getMeals());
        $this->assertEquals("restaurant", $r->getName());
        $this->assertEquals("restaurant", $r->__toString());
        $this->assertEquals(9, $r->getId());
        $this->assertEquals("http://website.local", $r->getWebsite());
        $this->assertEquals("0123456789", $r->getPhone());
        $this->assertEquals("info@website.local", $r->getEmail());
        $this->assertEquals($a, $r->getAddress());
        $this->assertEquals($e, $r->getExtraction());

        $ms = [new Meal()];
        $r->setMeals($ms);
        $this->assertEquals($ms, $r->getMeals());
    }
}