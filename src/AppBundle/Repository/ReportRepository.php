<?php

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