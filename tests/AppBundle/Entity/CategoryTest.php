<?php
/**
 * Created by PhpStorm.
 * User: ladmin
 * Date: 27.08.18
 * Time: 13:59
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{

    public function testGetId()
    {
        $c = new Category();
        $c->setId(48);
        $this->assertEquals(48, $c->getId());
    }

    public function testGetSearchTerms()
    {
        $c = new Category();
        $c->setSearchTerms(["hi", "test"]);
        $this->assertEquals(["hi", "test"], $c->getSearchTerms());
    }

    public function testAddSearchTerm()
    {
        $c = new Category();
        $c->addSearchTerm("search");
        $this->assertEquals(["search"], $c->getSearchTerms());
    }

    public function testGetMeals()
    {
        $c = new Category();
        $array = [new Meal()];
        $c->setMeals($array);
        $this->assertEquals($array, $c->getMeals());
    }

    public function testAddMeal() {
        $c = new Category();
        $m = new Meal();
        $c->addMeal($m);
        $this->assertEquals([$m], $c->getMeals());
    }

    public function testGetName()
    {
        $c = new Category();
        $c->setName("Testcat");
        $this->assertEquals("Testcat", $c->getName());
        $this->assertEquals("Testcat", $c->__toString());
    }
}
