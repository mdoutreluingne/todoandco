<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends BaseController
{
    protected $client;
    private $user;
    private $taskRepository;

    /**
     * setUp test
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->taskRepository = static::getContainer()->get(TaskRepository::class);

        // retrieve the test users
        $this->user = $userRepository->findOneBy(['username' => 'mdoutreluingne']);
    }

    public function testTasksListNotLoggedIn()
    {
        $this->client->request('GET', '/tasks');

        // we make sure that the app returns a redirect
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // redirect to login form /login
        $crawler = $this->client->followRedirect();
        $this->assertSame(2, $crawler->filter('form input#username')->count() + $crawler->filter('form input#password')->count());
    }

    public function testTasksListLoggedIn()
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertStringContainsString("Créer une tâche", $crawler->text());
    }

    public function testCreateTaskNotLoggedIn()
    {
        $this->client->request('GET', '/tasks/create');

        // we make sure that the app returns a redirect
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // redirect to login form /login
        $crawler = $this->client->followRedirect();
        $this->assertSame(2, $crawler->filter('form input#username')->count() + $crawler->filter('form input#password')->count());
    }

    public function testCreateTaskAuthorized()
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        $crawler = $this->client->request('GET', '/tasks/create');

        // test if the form exist for create task 
        $this->assertSame(2, $crawler->filter('#task_title')->count() + $crawler->filter('#task_content')->count());

        $createTaskForm = $crawler->selectButton("Ajouter")->form();
        $createTaskForm['task[title]'] = "Titre tâche de test";
        $createTaskForm['task[content]'] = "Contenu tâche de test";

        $crawler = $this->client->submit($createTaskForm);
        $crawler = $this->client->followRedirect();

        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString("La tâche a été bien été ajoutée.", $successMessage);
    }

    public function testEditTaskNotAuthorized()
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks/1/edit');

        // we make sure that the app returns a 403 not authorized
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditTaskAuthorized()
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        // find task user connected
        $idTaskUser = $this->taskRepository->findOneBy(['user' => $this->user])->getId();
        $crawler = $this->client->request('GET', '/tasks/' . $idTaskUser . '/edit');

        // test if the form exist for create task 
        $this->assertSame(2, $crawler->filter('#task_title')->count() + $crawler->filter('#task_content')->count());

        $editTaskForm = $crawler->selectButton("Modifier")->form();
        $editTaskForm['task[title]'] = "Titre tâche de test modifiée";
        $editTaskForm['task[content]'] = "Contenu tâche de test modifiée";

        $crawler = $this->client->submit($editTaskForm);
        $crawler = $this->client->followRedirect();

        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString("La tâche a bien été modifiée.", $successMessage);
    }

    public function testToggleTaskAuthorized()
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        // find task user connected
        $idTaskUser = $this->taskRepository->findOneBy(['user' => $this->user])->getId();
        $crawler = $this->client->request('GET', '/tasks/' . $idTaskUser . '/toggle');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode()); //on s'assure que ça retourne bien une redirection

        $crawler = $this->client->followRedirect();
        $this->assertStringContainsString("Superbe !", $crawler->text());
    }

    public function testDeleteTaskNotAuthorized()
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks/1/delete');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskAuthorized()
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        // find task user connected
        $idTaskUser = $this->taskRepository->findOneBy(['user' => $this->user])->getId();
        $this->client->request('GET', '/tasks/' . $idTaskUser . '/delete');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertStringContainsString("La tâche a bien été supprimée.", $crawler->text());
    }
}
