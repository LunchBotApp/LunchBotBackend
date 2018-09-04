<?php
/**
 * Created by PhpStorm.
 * User: ladmin
 * Date: 27.08.18
 * Time: 13:57
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\AdminUser;
use PHPUnit\Framework\TestCase;

class AdminUserTest extends TestCase
{

    public function testGetName()
    {
        $au = new AdminUser();
        $au->setName("Name");
        $this->assertEquals("Name", $au->getName());
    }

    public function testGetId()
    {
        $au = new AdminUser();
        $au->setId(42);
        $this->assertEquals(42, $au->getId());
    }
}
