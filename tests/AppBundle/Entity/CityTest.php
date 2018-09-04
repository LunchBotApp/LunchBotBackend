<?php
/**
 * Created by PhpStorm.
 * User: ladmin
 * Date: 27.08.18
 * Time: 14:03
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use AppBundle\Entity\Restaurant;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\CacheItem;

class CityTest extends TestCase
{

    public function testGetName()
    {
        $c = new City();
        $c->setName("Stadt");
        $this->assertEquals("Stadt", $c->getName());
    }

    public function testGetCountry()
    {
        $co = new Country();
        $c = new City();
        $c->setCountry($co);
        $this->assertEquals($co, $c->getCountry());
    }

    public function testGetId()
    {
        $c = new City();
        $c->setId(78);
        $this->assertEquals(78, $c->getId());
    }

    public function testGetRestaurants()
    {
        $r = [new Restaurant()];
        $c = new City();
        $c->setRestaurants($r);
        $this->assertEquals($r, $c->getRestaurants());
    }
}
