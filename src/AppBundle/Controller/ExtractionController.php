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

use AppBundle\Entity\Extraction;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Tag;
use AppBundle\Form\ExtractionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RestaurantController
 *
 * @package AppBundle\Controller
 */
class ExtractionController extends Controller
{
    /**
     * @Route("/restaurants/{rid}/extraction", name="extraction_add")
     * @Route("/extractions/{id}/edit", name="extraction_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     * @param Request $request
     * @param null    $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request, $rid = null, $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($id) {
            $extraction = $this->getDoctrine()->getRepository(Extraction::class)->getById($id);
            $restaurant = $extraction->getRestaurant();
        } else {
            $extraction = new Extraction();
            $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->getById($rid);
            $extraction->setRestaurant($restaurant);
        }
        $form = $this->createForm(ExtractionType::class, $extraction, [
            'tags' => $this->getDoctrine()->getRepository(Tag::class)->getByExtraction($extraction),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $restaurant->setExtraction($extraction);
            $em->persist($extraction);
            $em->merge($restaurant);
            $em->flush();

            return $this->redirectToRoute('restaurant_list');
        }

        return [
            'form'       => $form->createView(),
            'restaurant' => $restaurant
        ];
    }

    /**
     * @Route("/extractions/{id}/tags", name="tag_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     * @param Request $request
     * @param null    $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function tagsAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        return [
            'extraction' => $this->getDoctrine()->getRepository(Extraction::class)->getById($id),
        ];
    }
}