<?php


namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 *
 * @package AppBundle\Controller
 */
class UserController extends Controller
{

    /**
     * @Route("/users", name="user_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [
            'users' => $this->getDoctrine()->getRepository(User::class)->getAll()
        ];
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="User Detail list",
     * )
     * @Get("/api/v1/users/{userid}", defaults={"_format"="json"},requirements={"id"="\d+"})
     * @View(serializerGroups={"user", "settings", "user_settings"})
     * @param Request $request
     * @param         $userid
     * @return array
     */
    public
    function getUserAction(Request $request, $userid)
    {
        $user   = $this->getDoctrine()->getRepository(User::class)->getByUserId($userid);
        $exists = $user ? 1 : 0;
        if ($user) {
            return ['user' => $user, 'user_exists' => $exists];
        } else {
            return ['user_exists' => $exists];
        }
    }
}