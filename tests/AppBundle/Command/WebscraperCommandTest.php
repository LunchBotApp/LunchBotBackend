<?php
/**
 * Created by PhpStorm.
 * User: hdx
 * Date: 19.08.18
 * Time: 20:16
 */

namespace Tests\AppBundle\Command;

use AppBundle\Command\WebscraperCommand;
use AppBundle\DataFixtures\ORM\LoadCommandTestData;
use AppBundle\DataFixtures\ORM\LoadData;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\AppBundle\DatabasePrimer;

class WebscraperCommandTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
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
        $testPage = "https://us.lunchbot.de/tests/oxford.html";
    }

    public function testExecute(){
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);

        $command = $application->find('scrap:all');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName()
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Scraping...', $output);

        // ...
    }


    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
