<?php


namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Group;
use AppBundle\Entity\Settings;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser() {
        $u = new User();
        $u->setId(55);
        $u->setUserId("ABCD45");
        $g = new Group("13", "27");
        $u->setGroup($g);
        $s = new Settings();
        $u->setSettings($s);

        $this->assertEquals(55, $u->getId());
        $this->assertEquals("ABCD45", $u->getUserId());
        $this->assertEquals("ABCD45", $u->__toString());
        $this->assertEquals($g, $u->getGroup());
        $this->assertEquals($s, $u->getSettings());
    }
}