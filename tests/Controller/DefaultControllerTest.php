<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Tests\Controller\BaseController;
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

    public function testHomePageWhenNotLoggedIn(): void
    {
        $this->client->request('GET', '/');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode()); //on vérifie qu'il y a bien une redirection si on n'est pas loggué

        $crawler = $this->client->followRedirect();
        $this->assertSame(2, $crawler->filter('#username')->count() + $crawler->filter('#password')->count()); //on vérifie qu'il y a bien le champ username et password
    }

    public function testHomePageWhenLoggedIn(): void
    {
        $this->client->loginUser($this->admin);
        $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); //on vérifie qu'on arrive sur la page d'accueil
        $this->assertStringContainsString("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $this->client->getResponse()->getContent()); //on vérifie qu'il s'agit bien de la page d'accueil
    }
}
