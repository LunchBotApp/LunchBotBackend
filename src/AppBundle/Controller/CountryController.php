<?php


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