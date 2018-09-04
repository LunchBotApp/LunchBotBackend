<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Report;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CrawlerReportController
 *
 * @package AppBundle\Controller
 */
class CrawlerReportController extends Controller
{

    /**
     * @Route("/inbox/crawler", name="crawler_report_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [
            'reports' => $this->getDoctrine()->getRepository(Report::class)->getCrawlerReports()
        ];
    }
}