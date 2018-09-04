<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    public function test()
    {
        $user  = new User();
        $group = new Group("1","1");
        $group->setGroupId("1");
        $group->setUser([$user]);
        $group->setToken("1");
        $group->setId(1);


        $this->assertEquals(1, $group->getId());
        $this->assertEquals("1", $group->getGroupId());
        $this->assertEquals("1", $group->getToken());
        $this->assertEquals([$user], $group->getUser());
    }
}