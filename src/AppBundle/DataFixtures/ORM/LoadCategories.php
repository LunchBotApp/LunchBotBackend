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

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Load all categories into the database
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadCategories implements FixtureInterface, ContainerAwareInterface
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
        $categoryNames = [
            'Pasta', 'Pizza', 'Burger', 'Deutsch', 'Gemüse',
            'Griechisch', 'Fisch', 'Meeresfrüchte', 'Chinesisch',
            'Japanisch', 'Salat', 'Rotes Fleisch', 'Weißes Fleisch',
            'Auflauf', 'Krustentiere', 'Geflügel', 'Rind',
            'Schaf', 'Schwein',
        ];

        $categoriesSearchTerms = [
            'Pasta'          => [
                'Linguine', 'Cannellonni', 'Rigatoni', 'Tortelloni',
                'Calamari', 'Farfalle', 'Gnocchi', 'Lasagne',
                'Penne', 'Ravioli', 'Spaghetti', 'Tortellini',
                'Pasta', 'Tagliatelle', 'Nudel'
            ],
            'Gemüse'         => [
                'Gemüse', 'Salat'
            ],
            'Pizza'          => [
                'Pizza',
            ],
            'Burger'         => [
                'Burger', 'Hamburger',
            ],
            'Deutsch'        => [
                'Weißwurst', 'Bratwurst', 'Schnitzel', 'Braten',
                'Nordseekrabben', 'Currywurst', 'Klopse', 'Quarkkeulchen',
                'Klöße', 'Maultaschen', 'Schupfnudel', 'Deutsch',
                'Spätzle', 'Berlin', 'Thüring', 'Schwäbisch',
                'Frankfurt',
            ],
            'Griechisch'      => [
                'Gemista', 'Fasolakio', 'Bamies', 'Briam',
                'Unan Baildi', 'Giouvetsi', 'Moussaka', 'Pastitsio',
                'Kontosouvli', 'Stifado', 'Bifzefki', 'Gyros',
                'Souvlaki', 'Griechisch', 'Kreta',
            ],
            'Fisch'          => [
                'Lachs', 'Pollack', 'Hering', 'Thunfisch',
                'Forelle', 'Pangasius', 'Welse', 'Kabeljau',
                'Makrele', 'Rotbarsch', 'Köhler', 'Sardine',
                'Zander', 'Scholle', 'Karpfen', 'Schellfisch',
                'Tilapia', 'Seeteufel', 'Seehecht', 'Fisch',
                'Dorade', 'Matjes',
            ],
            'Meeresfrüchte'  => [
                'Austern', 'Hummer', 'Languste', 'Garnele',
                'Muschel', 'Seegurke', 'Krake', 'Krabbe',
                'Scampi', 'Titenfisch', 'Krebs', 'Schrimp',
                'Meeresfrucht', 'Meeresfrüchte',

            ],
            'Krustentiere'   => [
                'Hummer', 'Languste', 'Garnele', 'Krabbe',
                'Scampi', 'Krebs', 'Schrimp',
            ],
            'Chinesisch'     => [
                'Peking Ente', 'Süßsauer', 'Kung Pao', 'Gong Bao',
                'Mapo Tofu', 'Wan Tan', 'Jiazi', 'Frühlingsrollen',
                'Glasnudel', 'China', 'Chinesisch', 'Hong Kong',
            ],
            'Japanisch'      => [
                'Ramen', 'Sushi', 'Tempura', 'Kaiseki',
                'Unagi', 'Soba', 'Shabu', 'Okonmiyaki',
                'Tonkatsu', 'Yakitori', 'Udon', 'Japan',
            ],
            'Salat'          => [
                'Salat',
            ],
            'Rotes Fleisch'  => [
                'Rind', 'Kuh', 'Kalb', 'Bulle',
                'Schwein', 'Schaf', 'Lamm', 'Hack',
                'Ochs', 'Hachse', 'Beef', 'Rumpsteak',
                'Hammel', 'Cordon bleu'
            ],
            'Weißes Fleisch' => [
                'Vogel', 'Huhn', 'Ente', 'Taube',
                'Kaninchen', 'Hase', 'Pute', 'Hühner',
                'Strauß', 'Hahn', 'Gans', 'Wachtel',
                'Poularden', 'Hänchen', 'Geflügel',
                'Fasan',
            ],
            'Auflauf'        => [
                'Auflauf', 'Gratin',
            ],
            'Geflügel'       => [
                'Huhn', 'Ente', 'Pute', 'Hühner',
                'Vogel', 'Hahn', 'Gans', 'Wachtel',
                'Taube', 'Fasan', 'Geflügel', 'Hähnchen'
            ],
            'Rind'           => [
                'Rind', 'Kuh', 'Bulle', 'Ochs',
                'Beef', 'Kalb'
            ],
            'Schaf'          => [
                'Lamm', 'Schaf', 'Hammel',
            ],
            'Schwein'        => [
                'Schwein', 'Pork'
            ],
//            ''  =>  [
//                '', '', '', '',
//                '', '', '', '',
//                '', '', '', '',
//                '', '', '', '',
//            ]
        ];


        foreach ($categoryNames as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setSearchTerms($categoriesSearchTerms[$categoryName]);
            $manager->persist($category);
        }
        $manager->flush();
    }
}