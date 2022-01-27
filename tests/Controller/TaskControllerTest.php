<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    protected $client;
    private $user;
    private $admin;
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
        $this->admin = $userRepository->findOneBy(['username' => 'admin']);
    }

    /**
     * test tasks list not loggedIn
     *
     * @return void
     */
    public function testTasksListNotLoggedIn(): void
    {
        $this->client->request('GET', '/tasks');

        // we make sure that the app returns a redirect
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // redirect to login form /login
        $crawler = $this->client->followRedirect();
        $this->assertSame(2, $crawler->filter('form input#username')->count() + $crawler->filter('form input#password')->count());
    }

    /**
     * test tasks list loggedIn
     *
     * @return void
     */
    public function testTasksListLoggedIn(): void
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertStringContainsString("Créer une tâche", $crawler->text());
    }

    /**
     * test tasks done not list loggedIn
     *
     * @return void
     */
    public function testTasksDoneNotLoggedIn(): void
    {
        $this->client->request('GET', '/tasks/done');

        // we make sure that the app returns a redirect
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // redirect to login form /login
        $crawler = $this->client->followRedirect();
        $this->assertSame(2, $crawler->filter('form input#username')->count() + $crawler->filter('form input#password')->count());
    }

    /**
     * Test task done
     * 
     * @return void
     */
    public function testTasksDoneLoggedIn(): void
    {
        // simulate user being logged in
        $this->client->loginUser($this->admin);
        $crawler = $this->client->request('GET', '/tasks/done');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertStringContainsString("Créer une tâche", $crawler->text());
    }

    /**
     * test create task not loggedIn
     *
     * @return void
     */
    public function testCreateTaskNotLoggedIn(): void
    {
        $this->client->request('GET', '/tasks/create');

        // we make sure that the app returns a redirect
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // redirect to login form /login
        $crawler = $this->client->followRedirect();
        $this->assertSame(2, $crawler->filter('form input#username')->count() + $crawler->filter('form input#password')->count());
    }

    /**
     * test create task loggedIn
     *
     * @return void
     */
    public function testCreateTaskAuthorized(): void
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

    /**
     * test edit task not loggedIn
     *
     * @return void
     */
    public function testEditTaskNotAuthorized(): void
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks/1/edit');

        // we make sure that the app returns a 403 not authorized
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * test edit task loggedIn
     *
     * @return void
     */
    public function testEditTaskAuthorized(): void
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

    /**
     * test toggle task loggedIn
     *
     * @return void
     */
    public function testToggleTaskAuthorized(): void
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        // find task user connected
        $idTaskUser = $this->taskRepository->findOneBy(['user' => $this->user])->getId();
        $crawler = $this->client->request('GET', '/tasks/' . $idTaskUser . '/toggle');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertStringContainsString("Superbe !", $crawler->text());
    }

    /**
     * test delete task not loggedIn
     *
     * @return void
     */
    public function testDeleteTaskNotAuthorized(): void
    {
        // simulate user being logged in
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks/1/delete');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * test delete task loggedIn
     *
     * @return void
     */
    public function testDeleteTaskAuthorized(): void
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
