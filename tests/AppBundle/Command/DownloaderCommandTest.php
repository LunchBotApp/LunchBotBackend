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
 * Time: 20:12
 */

namespace Tests\AppBundle\Command;

use AppBundle\Command\DownloaderCommand;
use AppBundle\DataFixtures\ORM\LoadData;
use AppBundle\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\AppBundle\DatabasePrimer;

class DownloaderCommandTest extends KernelTestCase
{
    /**
     * @var DownloaderCommand
     */
    private $downloader;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     *
     */
    public function testExecute()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);

        $command       = $application->find('download:all');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName()
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Downloading...', $output);
        // ...
    }

    /**
     *
     */
    public function testSaveValid()
    {
        $validUrl = "http://us.lunchbot.de/tests/9.pdf";
        $this->downloader->saveFile($validUrl, __DIR__ . "/TestFiles/0.pdf");
        $this->assertTrue(file_exists(__DIR__ . "/TestFiles/0.pdf"));
    }

    /**
     *
     */
    public function testSaveInvalid()
    {
        $testrestaurant = new Restaurant();
        $testrestaurant->setId(0);
        $testrestaurant->setName("Test");
        $invalidUrl = "http://us.lunchbot.de/tests/random.pdf";
        $this->downloader->setEm($this->entityManager);
        $this->downloader->saveFile($invalidUrl, $testrestaurant, 'pdf', __DIR__ . "/TestFiles/");
        $this->assertFalse(file_exists(__DIR__ . "/TestFiles/0.pdf"));
    }

    /**
     *
     */
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

        $this->downloader = new DownloaderCommand();
    }

    /**
     *
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
        if (file_exists(__DIR__ . "/TestFiles/0.pdf")) {
            unlink(__DIR__ . "/TestFiles/0.pdf");
        }
    }
}
