<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\PriceRange;
use PHPUnit\Framework\TestCase;

class PriceRangeTest extends TestCase
{
    public function test()
    {
        $pr = new PriceRange();
        $pr->setId(1);
        $pr->setMax(10);
        $pr->setMin(1);
        $this->assertEquals(1, $pr->getId());
        $this->assertEquals(1, $pr->getMin());
        $this->assertEquals(10, $pr->getMax());
    }
}