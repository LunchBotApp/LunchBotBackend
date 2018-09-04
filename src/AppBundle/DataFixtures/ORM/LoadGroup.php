<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadGroup implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $group = new Group('group', 'group');
        $manager->persist($group);
        $manager->flush();


        $user = new User();
        $user->getGroup($group);
        $user->setUserId('user');
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setUserId('user1');
        $manager->persist($user);
        $manager->flush();
    }
}