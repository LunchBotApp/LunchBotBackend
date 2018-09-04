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
use Smalot\PdfParser\Parser;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class PdfParserCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param $restaurantId
     * @param $folder
     */
    public function deleteFile($restaurantId, $folder)
    {
        unlink($folder . $restaurantId . '.pdf');
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('parse:pdf')
            ->setDescription('Parses all PDF-files from the file system.');
        //->addArgument('url', InputArgument::REQUIRED, 'The url');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        $restaurants = $this->em->getRepository(Restaurant::class)->getAll();
        $output->writeln("Parsing pdf...");
        foreach ($restaurants as $restaurant) {
            $extraction = $restaurant->getExtraction();
            if ($extraction) {
                $restaurantId = $restaurant->getId();
                $keyTerms     = $extraction->getKeyTerms();
                $type         = $extraction->getType();
                // echo $restaurantName . "\n";
                if ($type == Extraction::TYPE_DOWNLOAD && $extraction->getFileType() == ".pdf") {
                    echo $type;
                    $arrayText = $this->parsePdf($restaurantId, $keyTerms, 'downloads/');
                    $i         = 0;
                    while ($i < count($arrayText)) {
                        $this->saveDataOfMeal($this->getMeal($arrayText[$i]), $this->getPrice($arrayText[$i]), $restaurant);
                        //Only there to test in Commandline
                        // echo $arrayText[$i];
                        //echo $this->getMeal($arrayText[$i]);
                        // echo $this->getPrice($arrayText[$i]);
                        $i++;
                    }
                    //Will delete File from Filesystem
                    //deleteFile($restaurantId,'downloads/');
                }
            }
        }
    }

    /**
     * @param $restaurantId
     * @param $keyTerms
     * @param $folder
     * @return array
     * @throws \Exception
     */
    public function parsePdf($restaurantId, $keyTerms, $folder)
    {
        $parser = new Parser();
        $pdf    = $parser->parseFile($this->getContainer()->get('kernel')->getRootDir() . '/../' . $folder . $restaurantId . '.pdf');
        $text   = $pdf->getText();

        return $this->extractText($text, $keyTerms);
    }

    /**
     * @param $text
     * @param $keyTerms
     * @return array
     */
    public function extractText($text, $keyTerms)
    {
        if (count($keyTerms) > 2) {
            $text      = $this->between($keyTerms[0], $keyTerms[1], $text);
            $arrayText = explode($keyTerms[2], $text);

            return $arrayText;
        } else {
            return [];
        }
    }

    /**
     * @param $from
     * @param $to
     * @param $text
     * @return bool|string
     */
    private function between($from, $to, $text)
    {
        return $this->before($to, $this->after($from, $text));
    }

    /**
     * @param $to
     * @param $text
     * @return bool|string
     */
    private function before($to, $text)
    {
        return substr($text, 0, strpos($text, $to));
    }

    /**
     * @param $from
     * @param $text
     * @return bool|string
     */
    private function after($from, $text)
    {
        if (!is_bool(strpos($text, $from))) {
            return substr($text, strpos($text, $from) + strlen($from));
        }
    }

    /**
     * @param $mealName
     * @param $price
     * @param $restaurant
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveDataOfMeal($mealName, $price, $restaurant)
    {
        $meal = $this->em->getRepository('AppBundle:Meal')->findOneBy(['restaurant' => $restaurant, 'date' => new DateTime(), 'name' => $mealName]);
        if (empty($meal) && !empty($mealName) && $price > 0) {
            $today = new DateTime();
            $today->format('Y-m-d H:i:s');
            $meal = new Meal();
            $meal->setName(trim($mealName));
            $meal->setPrice($price);
            $meal->setDate($today);
            $meal->setRestaurant($restaurant);
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
    private function getPrice($textPart)
    {
        return $prices = preg_replace('/[^0-9,.]/', '', $textPart);
    }
}