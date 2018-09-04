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
class AdminUserControllerTest extends WebTestCase
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
        $fixture->setContainer(self::$kernel->getContainer());
        $fixture->load($entityManager);
    }


    public function testList()
    {
        $client = $this->getClient('/users/admin');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin Users")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("admin@lunchbot.de")')->count());
    }

    public function testAdd()
    {
        $client = $this->getClient('/users/admin/add');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin User")')->count());
    }

    public function testAddValues()
    {
        $client = $this->getClient('/users/admin/add');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $form = $crawler->selectButton('Save')->form();

        // Set username and password
        $form['app_user_registration[email]'] = 'admin@lunchbot.lu';
        $form['app_user_registration[username]'] = 'admin2';
        $form['app_user_registration[plainPassword][first]'] = '11';
        $form['app_user_registration[plainPassword][second]'] = '11';
        $form['app_user_registration[name]'] = 'Admin2';
        $form['app_user_registration[enabled]']->tick();

        $client->submit($form);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin User")')->count());
    }



    public function testEdit()
    {
        $client = $this->getClient('/users/admin/1/edit');

        $response = $client->getResponse();
        $crawler  = $client->getCrawler();

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin User")')->count());
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