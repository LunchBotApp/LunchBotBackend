<?php

namespace Tests\AppBundle\Repository;

use AppBundle\DataFixtures\ORM\LoadRepositoryTestData;
use AppBundle\Entity\Message;
use AppBundle\Entity\Translation;
use AppBundle\Repository\TranslationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;

class TranslationRepositoryTest extends KernelTestCase
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

        $this->repo = $this->em->getRepository(Translation::class);
    }

    public function testGetByLocaleAndKey() {
        $ratingMessage = $this->em->getRepository(Message::class)->getByKey('Rating');

        $ratingTranslations = $ratingMessage->getTranslations();
        $ratingTranslationTexts = [];
        foreach ($ratingTranslations as $ratingTranslation) {
            $ratingTranslationTexts[] = $ratingTranslation->getValue();
        }
        $expectedRatingTranslationTexts = ['Please rate your last meal.', 'Bitte bewerten sie ihr letztes Gericht.'];

        $germanTranslations = $this->em->getRepository(Translation::Class)->getByLocale('ger');
        $germanTranslationTexts = [];
        foreach ($germanTranslations as $germanTranslation) {
            $germanTranslationTexts[] = $germanTranslation->getValue();
        }

        $germanRatingTranslation = $this->em->getRepository(Translation::Class)->getByLocaleAndKey('ger', 'Rating');

        $translationLanguages = [];
        foreach ($ratingTranslations as $translation) {
            $translationLanguages[] = $translation->getLanguage()->getName();
        }
        $expectedTranslationLanguages = ['English', 'Deutsch'];

        sort($translationLanguages);
        sort($expectedTranslationLanguages);

        sort($ratingTranslationTexts);
        sort($expectedRatingTranslationTexts);

        $this->assertEquals($expectedTranslationLanguages, $translationLanguages);
        $this->assertEquals($expectedRatingTranslationTexts, $ratingTranslationTexts);
        $this->assertContains($germanRatingTranslation->getValue(), $germanTranslationTexts);
        $this->assertContains($germanRatingTranslation->getValue(), $ratingTranslationTexts);

        $errorMessage = $this->em->getRepository(Translation::Class)->getByLocale('ERROR');
        $this->assertEquals(['undefined'], $errorMessage);
}
}
