<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Meal;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class LoadUserData
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadMeal implements FixtureInterface, ContainerAwareInterface
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
        $meal = new Meal();
        $meal->setName('Pizza');
        $meal->setDate(new \DateTime());
        $meal->setPrice(50);
        $manager->persist($meal);
        $manager->flush();
    }
}