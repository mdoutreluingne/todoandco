<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends BaseController
{
    protected $client;
    private $user;
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
        $this->user = $userRepository->findOneBy(['username' => 'mdoutreluingne']);
        $this->admin = $userRepository->findOneBy(['username' => 'admin']);
    }

    /**
     * test url access for create user with a basic user 
     *
     * @return void
     */
    public function testCreateUserNotAuthorized(): void
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/users/create');

        // we make sure that the app returns a 403 not authorized
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * test for create user with a admin 
     *
     * @return void
     */
    public function testCreateUserAuthorized(): void
    {
        // simulate admin being logged in
        $this->client->loginUser($this->admin);
        $crawler = $this->client->request('GET', '/users/create');

        $titlePage = $crawler->filter('h1')->text();
        $this->assertStringContainsString("Créer un utilisateur", $titlePage);

        $createUserForm = $crawler->selectButton("Ajouter")->form();
        $createUserForm['user[username]'] = "testUserAdd";
        $createUserForm['user[password][first]'] = "testtest";
        $createUserForm['user[password][second]'] = "testtest";
        $createUserForm['user[email]'] = "testUserAdd@todoandco.com";
        $createUserForm['user[roles]'] = "ROLE_USER";

        $crawler = $this->client->submit($createUserForm);
        $crawler = $this->client->followRedirect();

        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString("L'utilisateur a bien été ajouté.", $successMessage);
    }

    /**
     * test url access for edit user with a basic user
     *
     * @return void
     */
    public function testEditUserNotAuthorized(): void
    {
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/users/1/edit');

        // we make sure that the app returns a 403 not authorized
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * test for edit user with a admin
     *
     * @return void
     */
    public function testEditUserAuthorized(): void
    {
        $this->client->loginUser($this->admin);
        $crawler = $this->client->request('GET', '/users/2/edit');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); //on s'assure que l'appli retourne un 200

        $editUserForm = $crawler->selectButton("Modifier")->form();
        $editUserForm['user[username]'] = "testEdit";
        $editUserForm['user[password][first]'] = "testtest";
        $editUserForm['user[password][second]'] = "testtest";
        $editUserForm['user[email]'] = "testedit@todoandco.com";

        $crawler = $this->client->submit($editUserForm);
        $crawler = $this->client->followRedirect();

        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString("L'utilisateur a bien été modifié", $successMessage);
    }

    /**
     * test edit user with unknown user
     *
     * @return void
     */
    public function testEditUserDoesNotExistUserAuthorized(): void
    {
        $this->client->loginUser($this->admin);
        $this->client->request('GET', '/users/225/edit');

        // we make sure that the app returns a 404 not found
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}
