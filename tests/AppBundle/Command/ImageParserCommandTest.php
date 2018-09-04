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
 * Time: 20:14
 */

namespace Tests\AppBundle\Command;


use AppBundle\Command\ImageParserCommand;
use AppBundle\DataFixtures\ORM\LoadCommandTestData;
use AppBundle\DataFixtures\ORM\LoadData;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\AppBundle\DatabasePrimer;

class ImageParserCommandTest extends KernelTestCase
{
    /**
     * @var ImageParserCommand
     */
    private $imageParser;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var String
     */
    private $textToExtract;

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

        copy(__DIR__ . "/TestFiles/Fabrik.jpg", __DIR__ . "/TestFiles/testImageParse.jpg");
        $this->imageParser = new ImageParserCommand();
        $this->textToExtract = ' M I T T A G S K A R T E (gültig Di-Fr von 11.30-15.00 Uhr) Tagessuppe, serviert mit geröstetem Brot 4,30 € Hausgemachtes Giros mit Pommes frites und Tsatsiki 7,90 € Gegrillte Sutzukakia (Hackfleischröllchen) mit einer hausgemachten
Tomaten-Basilikum-Sauce, Reis und griechischem Joghurt 7,90 € Bifteki (Hacksteak) mit Mozzarella, Pommes, Joghurt-Dip 8,20 € Giros überbacken mit einer hausgemachten Champignonrahmsauce und
Mozzarella, dazu Pommes frites 7,90 € Hausgemachtes Bifteki (Hacksteak), mit Pommes frites und Tsatsiki
 8,20 € * alle Gerichte werden mit Salat serviert';
    }


    /**
     *
     */
    public function testDeleteFile()
    {
        $this->imageParser->deleteFile("testImageParse", __DIR__ . "/TestFiles/");
        $this->assertFalse(file_exists(__DIR__ . "/TestFiles/testImageParse.jpg"));
    }

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


        $command = $application->find('parse:image');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName()
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Parsing image...', $output);

        // ...
    }

    /**
     *
     */
    /*public function testExtractText()
    {
        $actualTextArray = $this->imageParser->extractText($this->textToExtract, ['11.30-15.00 Uhr)', '* alle Gerichte', '€']);
        $exceptedTextArray = [' Tagessuppe, serviert mit geröstetem Brot 4,30 ', ' Hausgemachtes Giros mit Pommes frites und Tsatsiki 7,90 ', ' Gegrillte Sutzukakia (Hackfleischröllchen) mit einer hausgemachten
Tomaten-Basilikum-Sauce, Reis und griechischem Joghurt 7,90 ', ' Bifteki (Hacksteak) mit Mozzarella, Pommes, Joghurt-Dip 8,20 ', ' Giros überbacken mit einer hausgemachten Champignonrahmsauce und
Mozzarella, dazu Pommes frites 7,90 ', ' Hausgemachtes Bifteki (Hacksteak), mit Pommes frites und Tsatsiki
 8,20 ', ' '];
        $this->assertEquals($exceptedTextArray, $actualTextArray);
    }*/

    /**
     *
     */
    protected function tearDown()
    {

        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
        if (file_exists(__DIR__ . "/TestFiles/testImageParse.jpg")) {

            $this->imageParser->deleteFile("testImageParse", __DIR__ . "/TestFiles/");
        }
    }
}
