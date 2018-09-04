<?php

namespace Tests\AppBundle\Repository;

use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;
use AppBundle\Entity\Country;
use AppBundle\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;

class CountryRepositoryTest extends KernelTestCase
{
    private $em;
    private $repo;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime(self::$kernel);

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $fixture = new LoadRepositoryTestData();
        $fixture->load($this->em);

        $this->repo = $this->em->getRepository(Country::class);
    }

    public function testGetByName()
    {
        $country = $this->repo->getByName('Deutschland');
        $countryName = $country->getName();
        $cities = $country->getCities();
        $cityNames = [];
        foreach ($cities as $city) {
            $cityNames[] = $city->getName();
        }
        $expectedCityNames = ['Karlsruhe','Stuttgart'];

        sort($cityNames);
        sort($expectedCityNames);

        $this->assertEquals($expectedCityNames, $cityNames);
        $this->assertEquals('Deutschland', $countryName);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
