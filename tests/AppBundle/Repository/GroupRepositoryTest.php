<?php

namespace Tests\AppBundle\Repository;

use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;
use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use AppBundle\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;

class GroupRepositoryTest extends KernelTestCase
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

        $this->repo = $this->em->getRepository(Group::class);
    }

    public function testGroupcheck()
    {
        $userA = $this->em->getRepository(User::class)->getByUserId('userIdA');
        $userF = $this->em->getRepository(User::class)->getByUserId('userIdF');
        $result1 = $this->repo->groupcheck($userA, 'groupId');
        $result2 = $this->repo->groupcheck($userA, 'ERROR');
        $result3 = $this->repo->groupcheck($userF, 'groupId');
        $groupToken = $this->repo->getByGroupId('groupId')->getToken();


        $this->assertEquals(true, $result1);
        $this->assertEquals(false, $result2);
        $this->assertEquals(true, $result3);
        $this->assertEquals('token', $groupToken);

    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
