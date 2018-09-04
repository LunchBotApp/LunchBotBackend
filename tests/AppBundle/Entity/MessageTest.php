<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function test()
    {
        $message = new Message();
        $message->setId(1);
        $message->setMessageKey("1");
        $message->setTranslations(["1"]);

        $this->assertEquals(1, $message->getId());
        $this->assertEquals("1", $message->getMessageKey());
        $this->assertEquals(["1"], $message->getTranslations());
    }
}