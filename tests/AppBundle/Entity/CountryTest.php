<?php
/**
 * Created by PhpStorm.
 * User: ladmin
 * Date: 27.08.18
 * Time: 14:05
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{

    public function testGetId()
    {
        $c = new Country();
        $c->setId(98);
        $this->assertEquals(98, $c->getId());
    }

    public function testGetCities()
    {
        $c = new Country();
        $cs = [new City()];
        $c->setCities($cs);
        $this->assertEquals($cs, $c->getCities());
    }

    public function testGetName()
    {
        $c = new Country();
        $c->setName("Land");
        $this->assertEquals("Land", $c->getName());
        $this->assertEquals("Land", $c->__toString());
    }
}
