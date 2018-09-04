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

namespace AppBundle\Command;

use AppBundle\Entity\Extraction;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Restaurant;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use thiagoalessio\TesseractOCR\TesseractOCR;

/**
 * Class JPGParserCommand
 *
 * @package AppBundle\Command
 */
class ImageParserCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Deltes a jpg File.
     *
     * @param $restaurantName
     * @param $folder
     */
    function deleteFile($restaurantName, $folder)
    {
        unlink($folder . $restaurantName . '.jpg');
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('parse:image')
            ->setDescription('Extracts the content of a image and saves it to the database.');
        //->addArgument('url', InputArgument::REQUIRED, 'The url');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");
        $output->writeln("Parsing image...");
        $restaurants = $this->em->getRepository(Restaurant::class)->getAll();

        foreach ($restaurants as $restaurant) {
            $extraction = $restaurant->getExtraction();
            if ($extraction) {
                $restaurantId = $restaurant->getId();
                $keyTerms     = $extraction->getKeyTerms();
                $type         = $extraction->getType();
                if ($type == Extraction::TYPE_DOWNLOAD && $extraction->getFileType() == ".jpg") {
                    $arrayText = $this->parseImage($restaurantId, $keyTerms, $this->getContainer()->get('kernel')->getRootDir() . '/../downloads/');
                    $i         = 0;
                    while ($i < count($arrayText)) {
                        $this->saveDataOfMeal($this->getMeal($arrayText[$i]), $extraction->getKeyTerms()[1], $restaurant);
                        //Only there to test in Commandline
                        // echo $arrayText[$i];
                        //echo $this->getMeal($arrayText[$i]);
                        // echo $this->getPrice($arrayText[$i]);
                        $i++;
                    }
                    //Will delete File from Filesystem
                    // deleteFile($restaurantName);
                }
            }
        }
    }

    /**
     * Gets text from an image.
     *
     * @param $restaurantId
     * @param $keyTerms
     * @return array
     */
    function parseImage($restaurantId, $keyTerms, $folder)
    {
        $filePath = $folder . $restaurantId . '.jpg';

        // Is useful to verify if the file exists, because the tesseract wrapper
        // will throw an error but without description
        /*if (!file_exists($filePath)) {
            echo "Warning: the providen file [" . $filePath . "] doesn't exists.";
        }*/

        // Create a  new instance of tesseract and provide as first parameter
        // the local path of the image
        $tesseractInstance = new TesseractOCR($filePath);
        $tesseractInstance->lang("fra");

        // Execute tesseract to recognize text
        $text = $tesseractInstance->run();

        return $this->extractText($text, $keyTerms);
    }

    /**
     * @param $text
     * @param $keyTerms
     * @return array
     */
    public function extractText($text, $keyTerms)
    {
        $arrayText = explode("\n", $text);
        $indexes   = explode(";", $keyTerms[2]);
        print_r($indexes);
        print_r($arrayText);
        $array = [];
        for ($i = 0; $i < count($indexes); $i++) {
            $array[] = $arrayText[$indexes[$i]];
        }

        print_r($array);

        return $array;
    }

    /**
     * Saves Data from a Meal to the Database.
     *
     * @param $mealName
     * @param $price
     * @param $restaurant
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveDataOfMeal($mealName, $price, $restaurant)
    {
        echo $mealName;
        echo $price;
        if (strpos($mealName, "lundi") !== false) {
            $mealName = str_replace("lundi ", "", $mealName);
            $gendate  = new DateTime();
            $gendate->setISODate(date('Y'), date('W'), 1); //year , week num , day
            echo $gendate->format('d-m-Y'); //"prints"  26-12-2013
        } elseif (strpos($mealName, "mardi") !== false) {
            $mealName = str_replace("mardi ", "", $mealName);
            $gendate  = new DateTime();
            $gendate->setISODate(date('Y'), date('W'), 2); //year , week num , day
            echo $gendate->format('d-m-Y'); //"prints"  26-12-2013
        } elseif (strpos($mealName, "mercredi") !== false) {
            $mealName = str_replace("mercredi ", "", $mealName);
            $gendate  = new DateTime();
            $gendate->setISODate(date('Y'), date('W'), 3); //year , week num , day
            echo $gendate->format('d-m-Y'); //"prints"  26-12-2013
        } elseif (strpos($mealName, "jeudi") !== false) {
            $mealName = str_replace("jeudi ", "", $mealName);
            $gendate  = new DateTime();
            $gendate->setISODate(date('Y'), date('W'), 4); //year , week num , day
            echo $gendate->format('d-m-Y'); //"prints"  26-12-2013
        } elseif (strpos($mealName, "vendredi") !== false) {
            $mealName = str_replace("vendredi ", "", $mealName);
            $gendate  = new DateTime();
            $gendate->setISODate(date('Y'), date('W'), 5); //year , week num , day
            echo $gendate->format('d-m-Y'); //"prints"  26-12-2013
        }

        $meal = $this->em->getRepository('AppBundle:Meal')->findOneBy(['restaurant' => $restaurant, 'date' => $gendate, 'name' => $mealName]);
        if (empty($meal) && !empty($mealName) && $price > 0) {
            $meal = new Meal();
            $meal->setName($mealName);
            $meal->setPrice($price);
            $meal->setDate($gendate);
            $meal->setRestaurant($restaurant);
            // tell Doctrine you want to  save the Meal
            $this->em->persist($meal);
            // actually executes the queries
            $this->em->flush();
        }
    }

    /**
     * @param $textPart
     * @return null|string|string[]
     */
    private function getMeal($textPart)
    {
        return $meals = preg_replace('/[^a-zA-Z" ]/', '', $textPart);
    }

    /**
     * @param $textPart
     * @return null|string|string[]
     */
    /*private function getPrice($textPart)
    {
        return $prices = preg_replace('/[^0-9,]/', '', $textPart);
    }*/

    /**
     *
     * /**
     * @param $from
     * @param $to
     * @param $text
     * @return bool|string
     */
   /* private function between($from, $to, $text)
    {
        return $this->before($to, $this->after($from, $text));
    }*/

    /**
     * @param $to
     * @param $text
     * @return bool|string
     */
    /*private function before($to, $text)
    {
        return substr($text, 0, strpos($text, $to));
    }*/

    /**
     * @param $from
     * @param $text
     * @return bool|string
     */
   /* private function after($from, $text)
    {
        return substr($text, strpos($text, $from) + strlen($from));
    }*/
}
