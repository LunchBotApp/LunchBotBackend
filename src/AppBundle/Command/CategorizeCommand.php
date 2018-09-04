<?php


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