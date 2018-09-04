<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Address;
use AppBundle\Entity\Language;
use AppBundle\Entity\PriceRange;
use AppBundle\Entity\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    public function testSettings() {
        $s = new Settings();
        $s->setId(11);
        $pr = new PriceRange();
        $s->setPriceRange($pr);
        $a = new Address();
        $s->setLocation($a);
        $s->setDistance(20);
        $l = new Language();
        $s->setLanguage($l);
        $s->setLocale("de");

        $this->assertEquals(11, $s->getId());
        $this->assertEquals($pr, $s->getPriceRange());
        $this->assertEquals($a, $s->getLocation());
        $this->assertEquals(20, $s->getDistance());
        $this->assertEquals($l, $s->getLanguage());
        $this->assertEquals("de", $s->getLocale());
    }
}