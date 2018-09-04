<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Entity\FeedbackReport;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\User;
use AppBundle\Form\RestaurantType;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RestaurantController
 *
 * @package AppBundle\Controller
 */
class RestaurantController extends Controller
{

    /**
     * @Route("/restaurants", name="restaurant_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [
            'restaurants' => $this->getDoctrine()->getRepository(Restaurant::class)->getAllRestaurants()
        ];
    }

    /**
     * @Route("/restaurants/add", name="restaurant_add")
     * @Route("/restaurants/{id}/edit", name="restaurant_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     * @param Request $request
     * @param null    $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id) {
            $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->getById($id);
            $address    = $restaurant->getAddress() ?? new Address();
        } else {
            $restaurant = new Restaurant();
            $address    = new Address();
        }
        $form = $this->createForm(RestaurantType::class, $restaurant, [
            'address' => $address,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($restaurant);
            $em->flush();

            return $this->redirectToRoute('restaurant_list');
        }

        return [
            'form' => $form->createView()
        ];
    }


    /**
     * @Route("/restaurants/{id}/details", name="restaurant_detail")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     * @param Request $request
     * @param         $id
     * @return array
     */
    public function detailAction(Request $request, $id)
    {
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($id);
        $meals      = $this->getDoctrine()->getRepository(Restaurant::class)->getTodayMeals($id);

        return [
            'restaurant' => $restaurant,
            'meals'      => $meals
        ];
    }


    /**
     * @SWG\Response(
     *     response=200,
     *     @Model(type=Restaurant::class, groups={"restaurant", "address"}),
     *     description="Restaurant list",
     * )
     * @Get("/api/v1/restaurants", defaults={"_format"="json"})
     * @View(serializerGroups={"restaurant", "address"})
     */
    public
    function getRestaurantsAction()
    {
        return $this->getDoctrine()->getRepository(Restaurant::class)->getAll();
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     @Model(type=Restaurant::class, groups={"restaurant", "address", "restaurant_meals","meal"}),
     *     description="Restaurant Detail list",
     * )
     * @Get("/api/v1/restaurants/{id}", defaults={"_format"="json"},requirements={"id"="\d+"})
     * @View(serializerGroups={"restaurant", "address", "restaurant_meals","meal"})
     * @param Request $request
     * @param         $id
     * @return null|object
     */
    public
    function getRestaurantDetailAction(Request $request, $id)
    {
        $date       = $request->query->get('date');
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->getById($id);
        if ($date == "today") {
            $restaurant->setMeals(new ArrayCollection($this->getDoctrine()->getRepository(Meal::class)->getAllTodayMealsByRestaurant($id)));
        }

        return $restaurant;
    }
}