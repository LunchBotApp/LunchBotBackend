<?php
/**
 * Created by PhpStorm.
 * User: hdx
 * Date: 20.08.18
 * Time: 22:25
 */

namespace Tests\AppBundle\Command;


use AppBundle\DataFixtures\ORM\LoadData;
use AppBundle\DataFixtures\ORM\LoadMeal;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\AppBundle\DatabasePrimer;

class CategorzierCommandTest extends KernelTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function testExecute()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);

        $command       = $application->find('categorize');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName()
        ]);

        $output = $commandTester->getDisplay();
        echo $output;
        $this->assertContains('Categorizing...', $output);
    }

    protected function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);


        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $fixture = new LoadData();
        $fixture->load($this->entityManager);


        $meal = new LoadMeal();
        $meal->load($this->entityManager);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
