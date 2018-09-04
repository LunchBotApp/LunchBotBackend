<?php
/**
 * This file is part of LunchBotBackend.
 *
 * Copyright (c) 2018 Benoît Frisch, Haris Dzogovic, Philipp Machauer, Pierre Bonert, Xiang Rong Lin
 *
 * LunchBotBackend is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 *
 * LunchBotBackend is distributed in the hope that it will be useful,but WITHOUT ANY WARRANTY; without even the implied warranty ofMERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with LunchBotBackend If not, see <http://www.gnu.org/licenses/>.
 *
 */

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
