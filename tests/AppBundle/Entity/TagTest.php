<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Extraction;
use AppBundle\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testTag() {
        $t = new Tag();
        $t->setId(55);
        $t->setValue("value");
        $p = new Tag();
        $t->setParent($p);
        $c = [new Tag()];
        $t->setChildren($c);
        $t->setPrint(true);
        $e = new Extraction();
        $t->setExtraction($e);
        $t->setType(Tag::TYPE_PRICE);
        $t->setFormat("format");

        $this->assertEquals(55, $t->getId());
        $this->assertEquals("value", $t->getValue());
        $this->assertEquals("value", $t->__toString());
        $this->assertEquals($p, $t->getParent());
        $this->assertEquals($c, $t->getChildren());
        $this->assertTrue($t->isPrint());
        $this->assertEquals($e, $t->getExtraction());
        $this->assertEquals(Tag::TYPE_PRICE, $t->getType());
        $this->assertEquals("format", $t->getFormat());
    }
}