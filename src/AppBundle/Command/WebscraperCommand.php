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
use AppBundle\Entity\Tag;
use DateTime;
use Doctrine\ORM\EntityManager;
use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class WebscraperCommand
 *
 * @package AppBundle\Command
 */
class WebscraperCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var DateTime
     */
    private $date = null;

    /**
     * @var string
     */
    private $descr;

    /**
     * @var double
     */
    private $price;


    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('scrap:all')
            ->setDescription('Scraps all websites which are in the system.');
        //->addArgument('url', InputArgument::REQUIRED, 'The url');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        $restaurants = $this->em->getRepository(Restaurant::class)->getAll();
        $output->writeln("Scraping...");
        foreach ($restaurants as $restaurant) {
            print $restaurant . "\n";
            $extraction = $restaurant->getExtraction();
            if ($extraction) {
                $url         = $extraction->getUrl();
                $this->date  = new DateTime();
                $this->descr = "";
                $this->price = "";
                if ($extraction->getType() == Extraction::TYPE_WEB) {
                    $tag     = $extraction->getTag();
                    $client  = new Client();
                    $crawler = $client->request('GET', $url);
                    $node    = $crawler->filter($tag);
                    $this->crawl($tag, $node, 0, $restaurant);
                } elseif ($extraction->getType() == Extraction::TYPE_API) {
                    echo "API\n";
                    $this->apiParse($extraction);
                }
            }
        }
    }

    /**
     * @param Tag        $tag
     * @param Crawler    $node
     * @param int        $level
     * @param Restaurant $restaurant
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private
    function crawl(Tag $tag, Crawler $node, $level = 0, Restaurant $restaurant)
    {
        $this->printBlock($tag, $level);
        $children = $tag->getChildren();
        foreach ($children as $childTag) {
            $this->printBlock($childTag, $level + 1);
            $childNode = $node->filter($childTag);

            if ($childTag->isPrint()) {
                $this->save($childTag, $childNode->text(), $restaurant);
                $this->printBlock($childNode->text(), $level + 3);
            }

            if (count($childTag->getChildren()) > 0 && $level < 4) {
                $this->crawl($childTag, $childNode, $level + 2, $restaurant);
            }
            //$this->crawl($child, $crawler->filter($this->tag->getValue())->children());
        }
        foreach ($node->siblings() as $child) {
            $this->printBlock($tag, $level);
            echo $child->nodeName . "\n";

            $children = $tag->getChildren();
            foreach ($children as $childTag) {
                if (count($childTag->getChildren()) == 0) {
                    $this->printBlock($childTag, $level + 1);
                }
                $childCrawler = new Crawler($child);
                $childNode    = $childCrawler->filter($childTag);

                if ($childTag->isPrint()) {
                    $this->save($childTag, $childNode->text(), $restaurant);
                    $this->printBlock($childNode->text(), $level + 3);
                }

                if (count($childTag->getChildren()) > 0) {
                    //   $this->crawl($childTag, $childNode, $level + 1);
                }
            }
        }
    }

    private
    function printBlock($string, $level = 0)
    {
        $offset = $level * 5;
        echo str_repeat(" ", $offset) . "+" . str_repeat("-", strlen($string) + 12) . "+\n";
        echo str_repeat(">", $level) . str_repeat(" ", $offset - $level) . "|" . str_repeat(" ", 6) . trim($string) . str_repeat(" ", 6) . "|\n";
        echo str_repeat(" ", $offset) . "+" . str_repeat("-", strlen($string) + 12) . "+\n";
    }

    /**
     * @param Tag        $tag
     * @param            $value
     * @param Restaurant $restaurant
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function save(Tag $tag, $value, Restaurant $restaurant)
    {
        if ($tag->getType() == Tag::TYPE_DATE) {
            echo "DATE";
            $date      = $value;
            $dateRegex = str_replace("d", '[0-9]{2}', str_replace('m', '[0-9]{2}', str_replace('Y', '[0-9]{2,4}', $tag->getFormat())));
            if (preg_match('/(.*)(' . $dateRegex . ')(.*)/', $date, $matches)) {
                $date = $matches[2];
            }
            // for example: you have a string with the following
            $dateFormat = $tag->getFormat();

            $dateTime = \DateTime::createFromFormat($dateFormat, $date);

            echo $dateTime->format('Y-m-d') . "\n";
            $this->date = $dateTime;
        } elseif ($tag->getType() == Tag::TYPE_DESCR) {
            echo "DESCR";
            echo trim($value);
            $this->descr = trim($value);
        } elseif ($tag->getType() == Tag::TYPE_PRICE) {
            echo "PRICE";
            $price = str_replace(",", ".", $value);

            if (preg_match('/(.*)([0-9]{1,2}.[0-9]{1,2})(.*)/', $price, $matches)) {
                $price = $matches[2];
                echo $price . "\n";
            }
            $this->price = $price;

            $meal = $this->em->getRepository('AppBundle:Meal')->findOneBy(['restaurant' => $restaurant, 'date' => $this->date, 'name' => $this->descr]);

            if (empty($meal) && !empty($this->descr) && $this->price > 0) {
                $meal = new Meal();
                $meal->setName($this->descr);
                $meal->setPrice($this->price);
                $meal->setRestaurant($restaurant);

                $meal->setDate($this->date);
                $this->em->persist($meal);
                $this->em->flush();
            }
        }
    }

    /**
     * @param Extraction $extraction
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private
    function apiParse(Extraction $extraction)
    {
        $headers = [
            'Content-Type: application/json',
            'User-Agent: curl/7.37.1'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $extraction->getUrl());
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($extraction->getRemoteUser()) {
            curl_setopt($ch, CURLOPT_USERPWD, $extraction->getRemoteUser() . ":" . $extraction->getRemotePass());
        }

        $result = curl_exec($ch);
        curl_close($ch);
        $result_arr = json_decode($result, true);
        $days       = $result_arr[$extraction->getTag()->getValue()];
        foreach ($days as $day => $lines) {
            $date = new DateTime();
            $date->setTimestamp($day);
            $date->modify('+1 day'); // fix because mensa api has wrong date 04-09-2018 bf
            echo $date->format('Y-m-d') . "\n";
            foreach ($lines as $line => $meals) {
                foreach ($meals as $meal) {
                    if (!array_key_exists('nodata', $meal) && !array_key_exists('closing_start', $meal)) {
                        if (array_key_exists('meal', $meal) && array_key_exists('dish', $meal) && array_key_exists('price_1', $meal) && array_key_exists('info', $meal)) {

                            $name     = $meal["meal"];
                            $addition = $meal["dish"];
                            $price    = $meal["price_1"];
                            $info     = $meal["info"];

                            $mealDB = $this->em->getRepository('AppBundle:Meal')->findOneBy(['restaurant' => $extraction->getRestaurant(), 'date' => $date, 'name' => $name]);
                            settype($price, "float");
                            if (empty($mealDB)) {
                                if ($price > 2.0) {
                                    echo ">>" . $line . "\n";
                                    echo $meal["meal"] . " " . $meal["dish"] . " " . $meal["price_1"] . " " . $meal["info"] . "\n";
                                    $mealDB = new Meal();
                                    $mealDB->setName($name);
                                    $mealDB->setAddition($addition . " " . $info);
                                    $mealDB->setPrice($price);
                                    $mealDB->setPlace($line);
                                    $mealDB->setRestaurant($extraction->getRestaurant());
                                    $mealDB->setDate($date);
                                    $this->em->persist($mealDB);
                                    $this->em->flush();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}
