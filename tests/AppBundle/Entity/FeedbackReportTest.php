<?php


namespace Tests\AppBundle\Entity;


use AppBundle\Entity\FeedbackReport;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class FeedbackReportTest extends TestCase
{
    public function test()
    {
        $user = new User();
        $user->setUserId("1");
        $date   = new \DateTime();
        $cError = new FeedbackReport();
        $cError->setId(1);
        $cError->setMessage("Hello");
        $cError->setUser($user);
        $cError->setDate($date);
        $cError->setFeedbackType(FeedbackReport::TYPE_DEFAULT);
        $cError->setFinished(false);

        $this->assertEquals(1, $cError->getId());
        $this->assertEquals("Hello", $cError->getMessage());
        $this->assertEquals($user, $cError->getUser());
        $this->assertEquals($date, $cError->getDate());
        $this->assertEquals(false, $cError->getFinished());
        $this->assertEquals(FeedbackReport::TYPE_DEFAULT, $cError->getFeedbackType());
    }

}