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
use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RestaurantController
 *
 * @package AppBundle\Controller
 */
class TagController extends Controller
{
    /**
     * @Route("/extractions/{eid}/tag", name="tag_add")
     * @Route("/extractions/{eid}/tag/{parent}", name="tag_add_parent")
     * @Route("/tags/{id}/edit", name="tag_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     * @param Request $request
     * @param null    $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request, $eid = null, $id = null, $parent = null)
    {
        $em = $this->getDoctrine()->getManager();


        if ($id) {
            $tag        = $this->getDoctrine()->getRepository(Tag::class)->getById($id);
            $extraction = $tag->getExtraction();
        } else {
            $tag = new Tag();
            if ($parent) {
                $parentTag = $this->getDoctrine()->getRepository(Tag::class)->getById($parent);
                $tag->setParent($parentTag);
            } else {
                if ($eid) {
                    $extraction = $this->getDoctrine()->getRepository(Extraction::class)->getById($eid);
                    $tag->setExtraction($extraction);
                }
            }
        }
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();
            if ($eid) {
                $extraction = $this->getDoctrine()->getRepository(Extraction::class)->getById($eid);
                return $this->redirectToRoute('tag_list', ['id' => $extraction->getId()]);
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }
}