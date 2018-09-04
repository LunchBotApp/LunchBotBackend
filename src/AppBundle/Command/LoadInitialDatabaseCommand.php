<?php


namespace AppBundle\Command;

use AppBundle\DataFixtures\ORM\LoadCategories;
use AppBundle\DataFixtures\ORM\LoadData;
use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadInitialDatabase
 *
 * @package AppBundle\Command
 */
class LoadInitialDatabaseCommand extends ContainerAwareCommand
{
    /**⁄
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
            ->setName('initialize:database')
            //the description what the command does
            ->setDescription('Loads database fixtures');
        //->addArgument('url', InputArgument::REQUIRED, 'The url');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");


        $fixture = new LoadData();
        $fixture->load($this->em);
        $categoriesFixture = new LoadCategories();
        $categoriesFixture->load($this->em);
    }
}