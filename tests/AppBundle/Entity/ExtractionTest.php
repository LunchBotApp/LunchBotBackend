<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Extraction;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Tag;
use PHPUnit\Framework\TestCase;

class ExtractionTest extends TestCase
{

    public function test()
    {
        $restaurant = new Restaurant();
        $tag        = new Tag();
        $extraction = new Extraction();
        $extraction->setId(1);
        $extraction->setType(Extraction::TYPE_DOWNLOAD);
        $extraction->setRestaurant($restaurant);
        $extraction->setFileType(".pdf");
        $extraction->setKeyTerms(["1"]);
        $extraction->setRemotePass("1");
        $extraction->setRemoteUser("1");
        $extraction->setTag($tag);
        $extraction->setUrl("http://lunchbot.de");

        $this->assertEquals(1, $extraction->getId());
        $this->assertEquals($restaurant, $extraction->getRestaurant());
        $this->assertEquals(Extraction::TYPE_DOWNLOAD, $extraction->getType());
        $this->assertEquals(".pdf", $extraction->getFileType());
        $this->assertEquals(["1"], $extraction->getKeyTerms());
        $this->assertEquals("1", $extraction->getRemotePass());
        $this->assertEquals("1", $extraction->getRemoteUser());
        $this->assertEquals($tag, $extraction->getTag());
        $this->assertEquals("http://lunchbot.de", $extraction->getUrl());
    }

}