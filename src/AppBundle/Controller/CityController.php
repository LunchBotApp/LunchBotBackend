<?php


namespace AppBundle\Controller;

use AppBundle\Entity\City;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CityController
 *
 * @package AppBundle\Controller
 */
class CityController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     @Model(type=City::class, groups={"city"}),
     *     description="Available cities",
     * )
     * @Get("/api/v1/cities", defaults={"_format"="json"})
     * @View(serializerGroups={"city"})
     * @param Request $request
     * @return array
     */
    public function getCities(Request $request)
    {
        return $this->getDoctrine()->getRepository(City::class)->getAll();
    }
}