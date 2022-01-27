<?php

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\TaskSubscriber;
use PHPUnit\Framework\TestCase;

class TaskSubscriberTest extends TestCase
{
    /**
     * @var TaskSubscriber
     */
    private $taskSubscriber;

    /**
     * setUp test
     * 
     * @return void
     */
    public function setUp(): void
    {
        $this->taskSubscriber = new TaskSubscriber();
    }

    /**
     * Test function skipLoad
     * 
     * @return void
     */
    public function testSkipLoad(): void
    {
        $skipLoad = true;
        
        $this->taskSubscriber->skipLoad();
        $this->assertEquals($skipLoad, $this->taskSubscriber->skipLoad);
    }
}
