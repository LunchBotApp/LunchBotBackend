<?php

namespace Tests\AppBundle\Repository;

use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;
use AppBundle\Entity\CrawlerErrorReport;
use AppBundle\Entity\Report;
use AppBundle\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;

class ReportRepositoryTest extends KernelTestCase
{
    private $em;
    private $repo;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime(self::$kernel);

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $fixture = new LoadRepositoryTestData();
        $fixture->load($this->em);

        $this->repo = $this->em->getRepository(Report::class);
    }

    public function testGetReports()
    {
        $crawlerErrorReports = $this->repo->getCrawlerReports();
        $crawlerErrorMessages = [];
        foreach ($crawlerErrorReports as $crawlerErrorReport) {
            $crawlerErrorMessages[] = $crawlerErrorReport->getMessage();
        }
        $expectedCrawlerErrorMessages = ['The crawler is not working correctly'];

        $issueReports = $this->repo->getIssueReports();
        $issueMessages = [];
        foreach ($issueReports as $issueReport) {
            $issueMessages[] = $issueReport->getMessage();
        }
        $expectedIssueMessages = ['There is a bug!'];

        $feedbackReports = $this->repo->getFeedbackReports();
        $feedbackMessages = [];
        foreach ($feedbackReports as $feedbackReport) {
            $feedbackMessages[] = $feedbackReport->getMessage();
        }
        $expectedFeedbackMessages = ['I love LunchBot!'];

        $restaurantFeedbackReports = $this->repo->getRestaurantFeedbackReports();
        $restaurantFeedbackMessages = [];
        foreach ($restaurantFeedbackReports as $restaurantFeedbackReport) {
            $restaurantFeedbackMessages[] = $restaurantFeedbackReport->getMessage();
        }
        $expectedRestaurantFeedbackMessages = ['XY would be a nice restaurant to add!'];

        sort($crawlerErrorReports);
        sort($issueReports);
        sort($feedbackReports);
        sort($expectedCrawlerErrorMessages);
        sort($expectedIssueMessages);
        sort($expectedFeedbackMessages);

        $this->assertEquals($expectedCrawlerErrorMessages, $crawlerErrorMessages);
        $this->assertEquals($expectedIssueMessages, $issueMessages);
        $this->assertEquals($expectedFeedbackMessages, $feedbackMessages);
        $this->assertEquals($expectedRestaurantFeedbackMessages, $restaurantFeedbackMessages);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
