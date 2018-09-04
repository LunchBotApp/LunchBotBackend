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

use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CategorizeCommand extends ContainerAwareCommand
{
    /**⁄
     *
     * @var EntityManager
     */
    private $em;

    /**
     *
     */
    protected function configure()
    {
        $this
            //the command name
            ->setName('categorize')
            //the description what the command does
            ->setDescription('Categories all meals');
        //->addArgument('url', InputArgument::REQUIRED, 'The url');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        $meals = $this->em->getRepository(Meal::class)->getAll();

        $output->writeln("Categorizing...");

        foreach ($meals as $meal) {
            $output->writeln($meal->getName());
            $this->em->getRepository(Category::class)->categorizeAndSave($meal);
        }
    }
}