<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    // Successfully retrieve a single task by its ID.
    public function test_retrieve_single_task_by_id()
    {
        // Create a new task
        $task = Task::factory()->create();

        // Send a GET request to the show method with the task ID
        $response = $this->get('/tasks/' . $task->id);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response body contains the task data
        $response->assertJson([
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'is_done' => $task->is_done,
                'created_at' => $task->created_at->toISOString(),
                'updated_at' => $task->updated_at->toISOString(),
            ]
        ]);
    }
}
