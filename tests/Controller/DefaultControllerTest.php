<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client;

    /**
     * setUp test
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = self::getContainer()->get(UserRepository::class);
        $this->user = $userRepository->findOneBy(['username' => 'mdoutreluingne']);
        $this->admin = $userRepository->findOneBy(['username' => 'admin']);
    }

    /**
     * test home page not loggin
     *
     * @return void
     */
    public function testHomePageWhenNotLoggedIn(): void
    {
        $this->client->request('GET', '/');

        // we treated that there is indeed a redirection if we are not logged in
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        // we check that there is indeed the username and password field
        $this->assertSame(2, $crawler->filter('#username')->count() + $crawler->filter('#password')->count());
    }

    /**
     * test home page loggin
     *
     * @return void
     */
    public function testHomePageWhenLoggedIn(): void
    {
        $this->client->loginUser($this->admin);
        $this->client->request('GET', '/');

        // we check that we arrive on the home page
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $this->client->getResponse()->getContent());
    }
}
