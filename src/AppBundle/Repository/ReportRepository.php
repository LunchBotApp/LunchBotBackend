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

namespace AppBundle\Repository;

use AppBundle\Entity\CrawlerErrorReport;
use AppBundle\Entity\FeedbackReport as FeedbackReport;
use AppBundle\Entity\IssueReport as IssueReport;

//use AppBundle\Entity\WebscraperReport as WebscraperReport;

/**
 * ReportRepository
 */
class ReportRepository extends BaseRepository
{
    /**
     * Returns all IssueReports
     *
     * @return array an array with all IssueReports
     */
    public function getCrawlerReports()
    {
        return $this->getEntityManager()
            ->getRepository(CrawlerErrorReport::class)
            ->createQueryBuilder('a')
            ->addOrderBy('a.finished', 'ASC')
            ->addOrderBy('a.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns all IssueReports
     *
     * @return array an array with all IssueReports
     */
    public function getIssueReports()
    {
        return $this->getEntityManager()
            ->getRepository(IssueReport::class)
            ->createQueryBuilder('a')
            ->addOrderBy('a.finished', 'ASC')
            ->addOrderBy('a.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns all IssueReports
     *
     * @return array an array with all IssueReports
     */
    public function getRestaurantFeedbackReports()
    {
        return $this->getEntityManager()
            ->getRepository(FeedbackReport::class)
            ->createQueryBuilder('a')
            ->where('a.feedbackType = :type')
            ->addOrderBy('a.finished', 'ASC')
            ->addOrderBy('a.date', 'ASC')
            ->setParameter('type', FeedbackReport::TYPE_RESTAURANT)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns all IssueReports
     *
     * @return array an array with all IssueReports
     */
    public function getFeedbackReports()
    {
        return $this->getEntityManager()
            ->getRepository(FeedbackReport::class)
            ->createQueryBuilder('a')
            ->where('a.feedbackType = :type')
            ->addOrderBy('a.finished', 'ASC')
            ->addOrderBy('a.date', 'ASC')
            ->setParameter('type', FeedbackReport::TYPE_DEFAULT)
            ->getQuery()
            ->getResult();
    }
}