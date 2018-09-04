<?php



namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadData;
use AppBundle\DataFixtures\ORM\LoadMeal;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;

/**
 * Class MealControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class MealControllerTest extends WebTestCase
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

        $meal = new LoadMeal();
        $meal->setContainer(self::$kernel->getContainer());
        $meal->load($entityManager);
    }

    public function testList()
    {
        $client = $this->getClient('/meals');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Meals")')->count());
    }

    public function testListUncat()
    {
        $client = $this->getClient('/meals/uncategorized');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Uncategorized Meals")')->count());
    }

    public function testAdd()
    {
        $client = $this->getClient('/meals/add');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $form = $crawler->selectButton('Save')->form();

        // Set username and password
        $form['meal[name]'] = 'Pizza';
        $form['meal[price]'] = '1';
        $form['meal[date]'] = '2018-08-23';


        $client->submit($form);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Meal")')->count());
    }

    public function testEdit()
    {
        $client = $this->getClient('/meals/1/edit');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $form = $crawler->selectButton('Save')->form();

        // Set username and password
        $form['meal[name]'] = 'Pizza';
        $form['meal[price]'] = '1';
        $form['meal[date]'] = '2018-08-23';


        $client->submit($form);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Meal")')->count());
    }

    public function testAllApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/meals');
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testDetailApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/meals/1');
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testAllTodayApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/meals/today');
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testAllRandomApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/meals/random');
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 500);
    }


    /**
     * @param $url
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    public function getClient($url): \Symfony\Bundle\FrameworkBundle\Client
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', $url);

        $form = $crawler->selectButton('Log in')->form();

        // Set username and password
        $form['_username'] = 'admin';
        $form['_password'] = 'admin';

        $client->submit($form);

        return $client;
    }
}