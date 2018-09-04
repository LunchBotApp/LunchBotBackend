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
