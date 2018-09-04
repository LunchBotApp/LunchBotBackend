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


use AppBundle\Entity\Country;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CountryController
 *
 * @package AppBundle\Controller
 */
class CountryController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     @Model(type=Country::class, groups={"country"}),
     *     description="Available countries",
     * )
     * @Get("/api/v1/countries", defaults={"_format"="json"})
     * @View(serializerGroups={"country"})
     * @param Request $request
     * @return array
     */
    public function getCountries(Request $request)
    {
        return $this->getDoctrine()->getRepository(Country::class)->getAll();
    }
}