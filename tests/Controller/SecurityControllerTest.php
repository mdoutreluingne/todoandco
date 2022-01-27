<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client;
    private $admin;

    /**
     * setUp test
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test users
        $this->admin = $userRepository->findOneBy(['username' => 'admin']);
    }

    /**
     * Test login page if appears
     * 
     * @return void
     */
    public function testLoginPageIsUp(): void
    {
        $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test authentication for the user
     * 
     * @return void
     */
    public function testAuthenticationUser(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', ['username' => 'mdoutreluingne', 'password' => 'testtest']);
        $crawler = $this->client->followRedirect();

        $this->assertStringContainsString('Se déconnecter', $crawler->text());
    }

    /**
     * Test authentication for the admin
     * 
     * @return void
     */
    public function testAuthenticationAdmin(): void
    {
        $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', ['username' => 'admin', 'password' => 'adminadmin']);
        $crawler = $this->client->followRedirect();

        // we check that it is indeed the home page admin version
        $this->assertStringContainsString('Se déconnecter', $crawler->text());
        $this->assertStringContainsString('Créer un utilisateur', $crawler->text());
        $this->assertStringContainsString('Gérer les utilisateurs', $crawler->text());
    }

    /**
     * Test authentication fail
     * 
     * @return void
     */
    public function testAuthenticationFail(): void
    {
        $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', ['username' => 'azerty', 'password' => 'azerty']);
        $crawler = $this->client->followRedirect();

        // we check that it is indeed the login page with the error message
        $this->assertStringContainsString('Invalid credentials.', $crawler->text());
    }

    /**
     * Test logout
     * 
     * @return void
     */
    public function testLogout(): void
    {
        // simulate user being logged in
        $this->client->loginUser($this->admin);

        $crawler = $this->client->request('GET', '/logout');
        $this->client->followRedirect();
        $crawler = $this->client->followRedirect();

        // User is redirected to the login page
        $this->assertEquals(2, $crawler->filter('form input#username')->count() + $crawler->filter('form input#password')->count());
    }
}
