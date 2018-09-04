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

use AppBundle\Entity\Translation;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TranslationController
 *
 * @package AppBundle\Controller
 */
class TranslationController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Translated Message",
     * )
     * @Post("/api/v1/translation", defaults={"_format"="json"})
     * @View(serializerGroups={"translation", "message", "language"})
     * @param Request $request
     * @return Translation
     */
    public function getTranslation(Request $request)
    {
        $userID  = $request->request->get('user');
        $message = $request->request->get('message');
        $user    = $this->getDoctrine()->getRepository(User::class)->getByUserId($userID);

        if ($user && $user->getSettings() && $user->getSettings()->getLanguage()) {
            $locale = $user->getSettings()->getLanguage()->getLocale();
        } else {
            $locale = "de";
        }

        return $this->get('translator')->trans($message, [], null, $locale);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Available locales",
     * )
     * @Get("/api/v1/locales", defaults={"_format"="json"})
     * @View(serializerGroups={"translation", "message", "language"})
     * @param Request $request
     * @return array
     */
    public function getLocales(Request $request)
    {
        return ["locales" => $this->getParameter('languages')];
    }
}