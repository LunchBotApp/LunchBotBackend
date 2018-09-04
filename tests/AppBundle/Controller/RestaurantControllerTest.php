<?php


namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;

/**
 * Class MealControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class RestaurantControllerTest extends WebTestCase
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
    }

    public function testList()
    {
        $client = $this->getClient('/restaurants');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Restaurants")')->count());
    }

    public function testAdd()
    {
        $client = $this->getClient('/restaurants/add');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Restaurant")')->count());
    }

    public function testEdit()
    {
        $client = $this->getClient('/restaurants/1/edit');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Restaurant")')->count());
    }

    public function testDetails()
    {
        $client = $this->getClient('/restaurants/1/details');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Oxford Café")')->count());
    }

    public function testAddValues()
    {
        $client = $this->getClient('/restaurants/add');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $form = $crawler->selectButton('Save')->form();

        // Set username and password
        $form['restaurant[name]']            = 'KIT RESTAURANT';
        $form['restaurant[address][number]'] = '5';
        $form['restaurant[address][street]'] = 'Am Fasanengarten';
        $form['restaurant[address][code]']   = '76131';
        $form['restaurant[website]']         = 'http://kit.edu';

        $client->submit($form);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Restaurant")')->count());
    }

    public function testAllApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/restaurants');
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame(count($responseArray), 12);
    }

    public function testDetailApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/restaurants/1');
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["id"], 1);
        $this->assertSame($responseArray["name"], "Oxford Café");
    }

    public function testDetailApiToday()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler       = $client->request('GET', '/api/v1/restaurants/1', ['date'=>'today']);
        $response      = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
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