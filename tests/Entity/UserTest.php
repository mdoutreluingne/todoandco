<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    /**
     * setUp test
     * 
     * @return void
     */
    public function setUp(): void
    {
        $this->user = new User();
    }

    /**
     * Test field id
     * 
     * @return void
     */
    public function testId()
    {
        $this->assertNull($this->user->getId());
    }

    /**
     * Test field username
     * 
     * @return void
     */
    public function testUsername()
    {
        $this->user->setUsername("test");
        $this->assertEquals("test", $this->user->getUsername());
    }

    /**
     * Test field email
     * 
     * @return void
     */
    public function testEmail()
    {
        $this->user->setEmail("test@live.fr");
        $this->assertEquals("test@live.fr", $this->user->getEmail());
    }

    /**
     * Test field password
     * 
     * @return void
     */
    public function testPassword()
    {
        $this->user->setPassword("passwordtest");
        $this->assertEquals("passwordtest", $this->user->getPassword());
    }

    /**
     * Test field task
     * 
     * @return void
     */
    public function testTask()
    {
        $task = new Task();

        $this->user->addTask($task);
        $this->assertCount(1, $this->user->getTasks());

        $this->user->removeTask($task);
        $this->assertCount(0, $this->user->getTasks());
    }

    /**
     * Test field roles
     * 
     * @return void
     */
    public function testRoles()
    {
        $this->user->setRoles(["ROLE_USER"]);
        $this->assertEquals(["ROLE_USER"], $this->user->getRoles());
    }
}
