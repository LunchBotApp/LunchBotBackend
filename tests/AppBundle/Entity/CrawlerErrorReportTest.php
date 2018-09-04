<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\CrawlerErrorReport;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class CrawlerErrorReportTest extends TestCase
{
    public function test()
    {
        $user = new User();
        $user->setUserId("1");
        $date   = new \DateTime();
        $cError = new CrawlerErrorReport();
        $cError->setId(1);
        $cError->setMessage("Hello");
        $cError->setUser($user);
        $cError->setDate($date);
        $cError->setErrorMessage("Hello");
        $cError->setFinished(false);

        $this->assertEquals(1, $cError->getId());
        $this->assertEquals("Hello", $cError->getMessage());
        $this->assertEquals($user, $cError->getUser());
        $this->assertEquals($date, $cError->getDate());
        $this->assertEquals(false, $cError->getFinished());
        $this->assertEquals("Hello", $cError->getErrorMessage());
    }

}