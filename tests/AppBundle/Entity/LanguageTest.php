<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Language;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    public function test()
    {
        $language = new Language();
        $language->setId(1);
        $language->setName("1");
        $language->setLocale("1");
        $language->setTranslations(["1"]);


        $this->assertEquals(1, $language->getId());
        $this->assertEquals("1", $language->getName());
        $this->assertEquals("1", $language->getLocale());
        $this->assertEquals(["1"], $language->getTranslations());
    }
}