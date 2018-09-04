<?php


namespace AppBundle\Controller;

use AppBundle\Entity\IssueReport;
use AppBundle\Entity\Report;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class RestaurantController
 *
 * @package AppBundle\Controller
 */
class IssueReportController extends Controller
{
    /**
     * @Route("/inbox/issue", name="issue_report_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [
            'reports' => $this->getDoctrine()->getRepository(Report::class)->getIssueReports()
        ];
    }


    /**
     * @SWG\Response(
     *     response=200,
     *     description="Add new Issue",
     * )
     * @Post("/api/v1/issue/add", defaults={"_format"="json"})
     * @param Request $request
     * @return null|object
     */
    public
    function getIssueRequest(Request $request)
    {
        $userId  = $request->request->get('user');
        $message = $request->request->get('message');
        if (!$userId && !$message) {
            return ["return_code" => 0, "message" => "Missing User and Message"];
        } elseif (!$userId) {
            return ["return_code" => 0, "message" => "Missing User"];
        } elseif (!$message) {
            return ["return_code" => 0, "message" => "Missing Message"];
        }
        $em   = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->getByUserId($userId);
        if (!$user) {
            $user = new User();
            $user->setUserId($userId);
            $em->persist($user);
            $em->flush();
        }
        $feedback = new IssueReport();
        $feedback->setUser($user);
        $feedback->setMessage($message);
        $em->persist($feedback);
        $em->flush();

        return ["return_code" => 1, "message" => "Message saved"];
    }
}