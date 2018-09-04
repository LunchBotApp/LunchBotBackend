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

namespace AppBundle\Controller;

use AppBundle\Entity\Meal;
use AppBundle\Form\MealType;
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
class MealController extends Controller
{

    /**
     * @Route("/meals", name="meal_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [
            'meals' => $this->getDoctrine()->getRepository(Meal::class)->getAllUpcoming()
        ];
    }

    /**
     * @Route("/meals/uncategorized", name="meal_list_uncategorized")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function listUncategorizedAction(Request $request)
    {
        return [
            'meals' => $this->getDoctrine()->getRepository(Meal::class)->getAllUncategorized()
        ];
    }

    /**
     * @Route("/meals/add", name="meal_add")
     * @Route("/meals/{id}/edit", name="meal_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function addAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id) {
            $meal = $this->getDoctrine()->getRepository(Meal::class)->getById($id);
        } else {
            $meal = new Meal();
        }
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($meal);
            $em->flush();

            return $this->redirectToRoute('meal_list');
        }

        return [
            'form' => $form->createView()
        ];
    }


    /**
     * @SWG\Response(
     *     response=200,
     *     @Model(type=Meal::class, groups={"meal", "restaurant", "address"}),
     *     description="Restaurant list",
     * )
     * @Get("/api/v1/meals", defaults={"_format"="json"})
     * @View(serializerGroups={"meal", "restaurant", "address"})
     */
    public
    function getMealsAction()
    {
        return $this->getDoctrine()->getRepository(Meal::class)->getAll();
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     @Model(type=Meal::class, groups={"meal", "restaurant", "address"}),
     *     description="Restaurant Detail list",
     * )
     * @Get("/api/v1/meals/{id}", defaults={"_format"="json"},requirements={"id"="\d+"})
     * @View(serializerGroups={"meal", "restaurant", "address"})
     * @param Request $request
     * @param         $id
     * @return null|object
     */
    public
    function getMealDetailAction(Request $request, $id)
    {
        return $this->getDoctrine()->getRepository(Meal::class)->getById($id);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     @Model(type=Meal::class, groups={"meal", "restaurant", "address"}),
     *     description="Restaurant list",
     * )
     * @Get("/api/v1/meals/today", defaults={"_format"="json"})
     * @View(serializerGroups={"meal", "restaurant", "address"})
     */
    public
    function getTodayMealsAction()
    {
        return $this->getDoctrine()->getRepository(Meal::class)->getAllTodayMeals();
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     @Model(type=Meal::class, groups={"meal", "restaurant", "address"}),
     *     description="Restaurant list",
     * )
     * @Get("/api/v1/meals/random", defaults={"_format"="json"})
     * @View(serializerGroups={"meal", "restaurant", "address"})
     */
    public
    function getRandomMealAction()
    {
        $meals = $this->getDoctrine()->getRepository(Meal::class)->getAllTodayMeals();

        return $meals[rand(0, count($meals)-1)];
    }
}