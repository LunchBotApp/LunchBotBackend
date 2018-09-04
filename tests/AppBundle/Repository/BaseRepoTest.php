<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\PriceRange;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Tests\AppBundle\DatabasePrimer;
use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;

class BaseRepoTest extends KernelTestCase
{
    private $em;

    private $repo;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        DatabasePrimer::prime(self::$kernel);

        $fixture = new LoadRepositoryTestData();
        $fixture->setContainer(self::$kernel->getContainer());
        $fixture->load($this->em);

        $this->repo = $this->em->getRepository(PriceRange::class);
    }

    public function testSaveNewObject()
    {
        $priceRange = new PriceRange();
        $priceRange->setMin(1);
        $priceRange->setMax(2);

        $this->repo->save($priceRange);

        $actualPriceRange = $this->repo->find($priceRange->getId());

        $this->em->remove($priceRange);

        $this->assertEquals($priceRange, $actualPriceRange);
    }

    public function testSaveOverwriteObject()
    {
        $priceRange = new PriceRange();
        $priceRange->setMin(1);
        $priceRange->setMax(2);

        $this->repo->save($priceRange);

        $priceRange->setMin(3);
        $priceRange->setMax(4);

        $this->repo->save($priceRange);

        $actualPriceRange = $this->repo->find($priceRange->getId());

        $this->em->remove($priceRange);

        $this->assertEquals($priceRange, $actualPriceRange);
    }

    public function testDelete()
    {
        $priceRange = new PriceRange();
        $priceRange->setMin(1);
        $priceRange->setMax(2);

        $this->em->persist($priceRange);
        $this->em->flush();

        $id = $priceRange->getId();

        $this->repo->delete($priceRange->getId());

        $actualPriceRange = $this->repo->find($id);

        $this->assertNull($actualPriceRange);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}