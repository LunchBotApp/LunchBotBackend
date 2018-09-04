<?php
/**
 * Created by PhpStorm.
 * User: ladmin
 * Date: 27.08.18
 * Time: 13:43
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{

    public function testId()
    {
        $a = new Address();
        $a->setId(3);
        $this->assertEquals(3, $a->getId());
    }

    public function testNumber()
    {
        $a = new Address();
        $a->setNumber(17);
        $this->assertEquals(17, $a->getNumber());
    }

    public function testCode()
    {
        $a = new Address();
        $a->setCode(12345);
        $this->assertEquals(12345, $a->getCode());
    }

    public function testStreet()
    {
        $a = new Address();
        $a->setStreet("Musterweg");
        $this->assertEquals("Musterweg", $a->getStreet());
    }

    public function testAddress()
    {
        $a = new Address();
        $a->setStreet("Musterweg");
        $a->setNumber(13);
        $a->setCode(12345);
        $c = new City();
        $c->setName("Musterhausen");
        $a->setCity($c);
        $this->assertEquals('13, Musterweg
12345 Musterhausen', $a->getAddress());

    }

    public function testCountry()
    {
        $a = new Address();
        $c = new Country();
        $a->setCountry($c);
        $this->assertEquals($c, $a->getCountry());
    }

    public function testCity()
    {
        $a = new Address();
        $c = new City();
        $a->setCity($c);
        $this->assertEquals($c, $a->getCity());
    }

}
