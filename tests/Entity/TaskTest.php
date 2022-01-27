<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private $task;

    /**
     * setUp test
     * 
     * @return void
     */
    public function setUp(): void
    {
        $this->task = new Task();
    }

    /**
     * Test field id
     * 
     * @return void
     */
    public function testId()
    {
        $this->assertNull($this->task->getId());
    }

    /**
     * Test field created_at
     * 
     * @return void
     */
    public function testCreatedAt()
    {
        $this->assertLessThanOrEqual(new \DateTime(), $this->task->getCreatedAt()); //la date de création de la tâche dans le setUp doit être antérieure à celle crée au moment du test

        $createdAtBefore = $this->task->getCreatedAt();
        $this->task->setCreatedAt(new \DateTime());
        $this->assertLessThanOrEqual($this->task->getCreatedAt(), $createdAtBefore);
    }

    /**
     * Test field title
     * 
     * @return void
     */
    public function testTitle()
    {
        $this->task->setTitle("titleTest");
        $this->assertEquals("titleTest", $this->task->getTitle());
    }

    /**
     * Test field content
     * 
     * @return void
     */
    public function testContent()
    {
        $this->task->setContent("content test");
        $this->assertEquals("content test", $this->task->getContent());
    }

    /**
     * Test field isDone
     * 
     * @return void
     */
    public function testIsDone(): void
    {
        $isDone = true;

        $this->task->toggle($isDone);
        $this->assertEquals($isDone, $this->task->isDone());
    }
}
