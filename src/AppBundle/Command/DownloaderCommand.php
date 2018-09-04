<?php


namespace AppBundle\Command;

use AppBundle\Entity\CrawlerErrorReport;
use AppBundle\Entity\Extraction;
use AppBundle\Entity\Restaurant;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Downloader
 *
 * @package AppBundle\Command
 */
class DownloaderCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $destFolder = "downloads/";

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            //the command name
            ->setName('download:all')
            //the description what the command does
            ->setDescription('Downloads all files from given urls');
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

        $restaurants = $this->em->getRepository(Restaurant::class)->getAll();
        $output->writeln("Downloading...");
        foreach ($restaurants as $restaurant) {
            $extraction = $restaurant->getExtraction();

            if ($extraction) {
                $url      = $extraction->getUrl();
                $fileType = $extraction->getFileType();
                $type     = $extraction->getType();
                if ($type == Extraction::TYPE_DOWNLOAD) {
                    $output->writeln(realpath($this->destFolder . $restaurant->getId() . $fileType));
                    $this->saveFile($url, $this->getContainer()->get('kernel')->getRootDir() . '/../' . $this->destFolder . $restaurant->getId() . $fileType);
                    $output->writeln($restaurant->getName());
                }
            }
        }
        $output->writeln("Download finished!");
    }

    /**
     * Saves a file from an url to the filesystem.
     *
     * @param string $url
     * @param string $path
     */
    public function saveFile(string $url, string $path)
    {
        if ($url == 'http://bratar.de/karten/erbprinzenstr/mittageps.pdf') {
            copy($url, $path);
        }
        if (!$this->urlExists($url)) {
            //Download schlug fehl.
            $this->saveErrorMessage("Website not available check the URL: " . $url);
            echo "Download failed.".$url;
        } else {
            copy($url, $path);
        }
    }

    private function urlExists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);

        return $status;
    }

    public function saveErrorMessage($errorMessage)
    {
        $downloaderReport = new CrawlerErrorReport();
        $downloaderReport->setMessage($errorMessage);
        $downloaderReport->setErrorMessage($errorMessage);
        // tell Doctrine you want to  save the Meal
        $this->em->persist($downloaderReport);
        // actually executes the queries
        $this->em->flush();
    }

}