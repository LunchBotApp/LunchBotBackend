<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use DateTime;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GroupController
 *
 * @package AppBundle\Controller
 */
class GroupController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Token",
     * )
     * @Post("/api/v1/groups/token/get", defaults={"_format"="json"})
     * @param Request $request
     * @return array
     */
    public function getToken(Request $request)
    {
        $groupID = $request->request->get('group');

        if (!$groupID) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        $group = $this->getDoctrine()->getRepository(Group::class)->getByGroupId($groupID);

        if ($group === NULL) {
            return ["return_code" => 0, "message" => "This group doesn't exist", "user_created" => 0];
        }

        $token = $group->getToken();

        return ["return_code" => 1, "message" => "Success", "user_created" => 0, "token" => $token];
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Token",
     * )
     * @Post("/api/v1/groups/add", defaults={"_format"="json"})
     * @param Request $request
     * @return array
     */
    public function addNewGroup(Request $request)
    {
        $groupID = $request->request->get('group');
        $token = $request->request->get('token');

        if (!$groupID || !$token) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        $group = new Group($groupID, $token);
        $em = $this->getDoctrine()->getManager();

        $em->persist($group);
        $em->flush();

        return ["return_code" => 1, "message" => "New Group added", "user_created" => 0];
    }
}