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
class IssueReportControllerTest extends WebTestCase
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
        $client = $this->getClient('/inbox/issue');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Issue Reports")')->count());
    }

    public function testIssueAddApi()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler = $client->request('POST', '/api/v1/issue/add', ['user' => 'test', 'message' => 'test']);
        $response = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 1);
        $this->assertSame($responseArray["message"], "Message saved");

    }

    public function testMissingMessage()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler = $client->request('POST', '/api/v1/issue/add', ['user' => 'test']);
        $response = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing Message");

    }

    public function testMissingUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler = $client->request('POST', '/api/v1/issue/add', ['message' => 'test']);
        $response = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing User");

    }

    public function testMissingMessageUser()
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler = $client->request('POST', '/api/v1/issue/add', []);
        $response = $client->getResponse();
        $responseArray = json_decode($response->getContent(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseArray["return_code"], 0);
        $this->assertSame($responseArray["message"], "Missing User and Message");

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