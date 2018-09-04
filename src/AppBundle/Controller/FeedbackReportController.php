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

use AppBundle\Entity\FeedbackReport;
use AppBundle\Entity\Report;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Post;
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
class FeedbackReportController extends Controller
{
    /**
     * @Route("/inbox/feedback", name="feedback_report_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [
            'reports' => $this->getDoctrine()->getRepository(Report::class)->getFeedbackReports()
        ];
    }


    /**
     * @Route("/inbox/restaurant", name="feedback_report_restaurants")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function listNewAction(Request $request)
    {
        return [
            'reports' => $this->getDoctrine()->getRepository(Report::class)->getRestaurantFeedbackReports()
        ];
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Add new Issue",
     * )
     * @Post("/api/v1/feedback/add", defaults={"_format"="json"})
     * @param Request $request
     * @return array
     */
    public
    function getFeedbackRequest(Request $request)
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
        $feedback = new FeedbackReport();
        $feedback->setFeedbackType(FeedbackReport::TYPE_DEFAULT);
        $feedback->setUser($user);
        $feedback->setMessage($message);
        $em->persist($feedback);
        $em->flush();

        return ["return_code" => 1, "message" => "Message saved"];
    }


    /**
     * @SWG\Response(
     *     response=200,
     *     description="Request new Restaurant",
     * )
     * @Post("/api/v1/restaurants/request", defaults={"_format"="json"})
     * @param Request $request
     * @return null|object
     */
    public
    function getRestaurantRequest(Request $request)
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
        $feedback = new FeedbackReport();
        $feedback->setFeedbackType(FeedbackReport::TYPE_RESTAURANT);
        $feedback->setUser($user);
        $feedback->setMessage($message);
        $em->persist($feedback);
        $em->flush();

        return ["return_code" => 1, "message" => "Message saved"];
    }

}