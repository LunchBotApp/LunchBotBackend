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


use AppBundle\Entity\AdminUser;
use AppBundle\Form\AdminUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminUserController
 *
 * @package AppBundle\Controller
 */
class AdminUserController extends Controller
{

    /**
     * @Route("/users/admin", name="admin_user_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [
            'users' => $this->getDoctrine()->getRepository(AdminUser::class)->getAll()
        ];
    }

    /**
     * @Route("/users/admin/add", name="admin_user_add")
     * @Route("/users/admin/{id}/edit", name="admin_user_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function addAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id) {
            $user = $this->getDoctrine()->getRepository(AdminUser::class)->getById($id);
        } else {
            $user = new AdminUser();
        }
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEnabled(true);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return [
            'form' => $form->createView()
        ];
    }

}