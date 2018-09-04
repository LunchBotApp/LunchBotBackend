<?php


namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadData;
use AppBundle\Entity\Address;
use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use AppBundle\Entity\PriceRange;
use AppBundle\Entity\Settings;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;

/**
 * Class MealControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class SettingsControllerTest extends WebTestCase
{
    public function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);

        $client        = static::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $fixture = new LoadData();
        $fixture->load($entityManager);

        $user = new User();
        $user->setUserId('user');
        $entityManager->persist($user);
        $entityManager->flush();

        $user = new User();
        $user->setUserId('settings');
        $settings = new Settings();
        $user->setSettings($settings);
        $entityManager->persist($settings);
        $entityManager->persist($user);
        $entityManager->flush();

        $user = new User();
        $user->setUserId('pricerange');
        $settings   = new Settings();
        $priceRange = new PriceRange();
        $priceRange->setMin(1);
        $priceRange->setMax(10);
        $settings->setPriceRange($priceRange);
        $user->setSettings($settings);
        $entityManager->persist($priceRange);
        $entityManager->persist($settings);
        $entityManager->persist($user);
        $entityManager->flush();

        $user = new User();
        $user->setUserId('location');
        $settings = new Settings();
        $location = new Address();
        $location->setCountry($entityManager->getRepository(Country::class)->getByName("Germany"));
        $location->setCity($entityManager->getRepository(City::class)->getByName("Karlsruhe"));
        $location->setStreet('1');
        $location->setNumber('1');
        $location->setCode('123');
        $settings->setLocation($location);
        $user->setSettings($settings);

        $entityManager->persist($location);
        $entityManager->persist($settings);
        $entityManager->persist($user);
        $entityManager->flush();
    }


    public function testPriceRangeMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/pricerange', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testPriceRangeNotNumeric()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/pricerange', ['user' => 'user', 'min' => 'hello', 'max' => 'hello']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Min and max need to be numbers");
    }

    public function testPriceRangeNormal()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/pricerange', ['user' => 'user', 'min' => '1', 'max' => '10']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 0);
        $this->assertSame($responseArray["message"], "New priceRange saved");
    }

    public function testPriceRangeNewUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/pricerange', ['user' => 'user1', 'min' => '1', 'max' => '10']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 1);
        $this->assertSame($responseArray["message"], "New priceRange saved");
    }

    public function testPriceRangeSettingsUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/pricerange', ['user' => 'settings', 'min' => '1', 'max' => '10']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 0);
        $this->assertSame($responseArray["message"], "New priceRange saved");
    }

    public function testPriceRangePRUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/pricerange', ['user' => 'pricerange', 'min' => '1', 'max' => '10']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 0);
        $this->assertSame($responseArray["message"], "New priceRange saved");
    }


    public function testLocationMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/location', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testLocationWrongCountry()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/location', ['user' => 'user', 'country' => '2', 'city' => '1', 'street' => 'Am Fasanengarten', 'number' => '5', 'code' => '76131']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Stated country or city don't exist");
    }

    public function testLocationNormal()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/location', ['user' => 'user', 'country' => 'Germany', 'city' => 'Karlsruhe', 'street' => 'Am Fasanengarten', 'number' => '5', 'code' => '76131']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 0);
        $this->assertSame($responseArray["message"], "New location saved");
    }

    public function testLocationNewUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/location', ['user' => 'user1', 'country' => 'Germany', 'city' => 'Karlsruhe', 'street' => 'Am Fasanengarten', 'number' => '5', 'code' => '76131']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 1);
        $this->assertSame($responseArray["message"], "New location saved");
    }

    public function testLocationSettings()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/location', ['user' => 'settings', 'country' => 'Germany', 'city' => 'Karlsruhe', 'street' => 'Am Fasanengarten', 'number' => '5', 'code' => '76131']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["message"], "New location saved");
    }

    public function testLocationLUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/location', ['user' => 'location', 'country' => 'Germany', 'city' => 'Karlsruhe', 'street' => 'Am Fasanengarten', 'number' => '5', 'code' => '76131']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["message"], "New location saved");
    }

    public function testDistanceMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/distance', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testDistanceNotNumeric()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/distance', ['user' => 'user', 'distance' => 'hello']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Distance needs to be a number");
    }

    public function testDistanceNormal()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/distance', ['user' => 'user', 'distance' => '1']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 0);
        $this->assertSame($responseArray["message"], "New distance saved");
    }

    public function testDistanceNewUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/distance', ['user' => 'user1', 'distance' => '1']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 1);
        $this->assertSame($responseArray["message"], "New distance saved");
    }

    public function testDistanceSettings()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/distance', ['user' => 'settings', 'distance' => '1']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 0);
        $this->assertSame($responseArray["message"], "New distance saved");
    }

    public function testLanguageMissing()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/language', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing information");
    }

    public function testLanguageNotExist()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/language', ['user' => 'user', 'language' => 'lb']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Stated locale doesn't exist");
    }

    public function testLanguage1()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/language', ['user' => 'user', 'language' => 'fr']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 0);
        $this->assertSame($responseArray["message"], "New language saved");
    }

    public function testLanguage2()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/language', ['user' => 'user', 'language' => 'de']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 0);
        $this->assertSame($responseArray["message"], "New language saved");
    }

    public function testLanguageNewUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/language', ['user' => '1', 'language' => 'fr']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 1);
        $this->assertSame($responseArray["message"], "New language saved");
    }

    public function testLanguageSettings()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('POST', '/api/v1/settings/language', ['user' => 'settings', 'language' => 'fr']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["user_created"], 0);
        $this->assertSame($responseArray["message"], "New language saved");
    }

    public function testSettingsList()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/settings/user/list', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["user_created"], 0);
    }


    public function testSettingsListNewUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/settings/user11/list', []);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["user_created"], 1);
    }
}