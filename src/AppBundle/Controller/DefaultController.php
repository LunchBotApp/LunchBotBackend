<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meal;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard_admin")
     * @Template()
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        return [

        ];
    }


    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function menusAction(Request $request)
    {
        return [
            'today' => new  \DateTime(),
            'meals' => $this->getDoctrine()->getRepository(Meal::class)->getAllTodayMeals()
        ];
    }
}
