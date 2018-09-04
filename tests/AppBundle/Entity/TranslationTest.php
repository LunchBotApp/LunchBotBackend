<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Language;
use AppBundle\Entity\Message;
use AppBundle\Entity\Translation;
use PHPUnit\Framework\TestCase;

class TranslationTest extends TestCase
{
    public function testTranslation() {
        $t = new Translation();
        $t->setId(91);
        $t->setValue("value");
        $l = new Language();
        $t->setLanguage($l);
        $m = new Message();
        $t->setMessage($m);

        $this->assertEquals(91, $t->getId());
        $this->assertEquals("value", $t->getValue());
        $this->assertEquals($l, $t->getLanguage());
        $this->assertEquals($m, $t->getMessage());
    }
}