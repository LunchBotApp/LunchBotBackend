<?php

namespace Tests\AppBundle\Repository;

use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;
use AppBundle\Entity\Message;
use AppBundle\Entity\Translation;
use AppBundle\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;

class MessageRepositoryTest extends KernelTestCase
{
    private $em;
    private $repo;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime(self::$kernel);

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $fixture = new LoadRepositoryTestData();
        $fixture->load($this->em);

        $this->repo = $this->em->getRepository(Message::class);
    }

    public function testGetByKey()
    {
        $welcomeMessage = $this->repo->getByKey('Welcome');
        $welcomeTranslations = $welcomeMessage->getTranslations();
        $welcomeTranslationTexts = [];
        foreach ($welcomeTranslations as $welcomeTranslation) {
            $welcomeTranslationTexts[] = $welcomeTranslation->getValue();
        }
        $expectedWelcomeTranslationTexts = ['Welcome to the Lunchbot!', 'Willkommen beim Lunchbot!'];
        $messageKey = $welcomeMessage->getMessageKey();

        sort($welcomeTranslationTexts);
        sort($expectedWelcomeTranslationTexts);

        $this->assertEquals('Welcome', $messageKey);
        $this->assertEquals($expectedWelcomeTranslationTexts, $welcomeTranslationTexts);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
