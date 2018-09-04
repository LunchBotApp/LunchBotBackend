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

use AppBundle\Entity\Rating;
use AppBundle\Entity\SoloSuggestion;
use AppBundle\Entity\Suggestion;
use AppBundle\Entity\User;
use Exception;
use FOS\RestBundle\Controller\Annotations\Post;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RatingController
 *
 * @package AppBundle\Controller
 */
class RatingController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Translated Message",
     * )
     * @Post("/api/v1/ratings/add", defaults={"_format"="json"})
     * @param Request $request
     * @return array
     */
    public function addUserRatingForGS(Request $request)
    {
        $userID       = $request->request->get('user');
        $value        = $request->request->get('value');
        $suggestionID = $request->request->get('suggestion');

        if (!$userID || !$value || !$suggestionID) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        $user       = $this->getDoctrine()->getRepository(User::class)->getByUserId($userID);
        $suggestion = $this->getDoctrine()->getRepository(Suggestion::class)->getById($suggestionID);
        $meal       = null;
        $em         = $this->getDoctrine()->getManager();

        if (!$user || !$suggestion) {
            return ["return_code" => 0, "message" => "Missing information", "user_created" => 0];
        }

        if ($suggestion instanceof SoloSuggestion) {
            $meal = $suggestion->getMealChoices()[0]->getMeal();
        } else {
            $mealChoices = $suggestion->getMealChoices();
            foreach ($mealChoices as $mealChoice) {
                if ($mealChoice->getUser()->getId() == $user->getId()) {
                    $meal = $mealChoice->getMeal();
                }
            }
        }

        if (!$meal) {
            return ["return_code" => 0, "message" => "The user didn't select any meal", "user_created" => 0];
        }

        $rating = new Rating();
        $rating->setUser($user);
        $rating->setMeal($meal);
        $rating->setValue($value);
        $rating->setTimestamp($suggestion->getTimestamp());
        $em->persist($rating);
        $suggestion->addRating($rating);
        $em->merge($suggestion);
        $em->flush();

        return ["return_code" => 1, "message" => "Rating saved", "user_created" => 0];
    }
}