<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_list_tasks()
    {
        Task::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/tasks');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    /** @test */
    public function it_can_filter_tasks_by_completion_status()
    {
        Task::factory()->create(['user_id' => $this->user->id, 'is_completed' => true]);
        Task::factory()->create(['user_id' => $this->user->id, 'is_completed' => false]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/tasks?completed=true');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $payload = [
            'name' => 'Test Task',
            'description' => 'This is a test task.',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/tasks', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', $payload);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $payload = [
            'name' => 'Updated Task',
            'description' => 'Updated description.',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/tasks/{$task->id}", $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', $payload);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function it_can_toggle_task_completion_status()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id, 'is_completed' => false]);

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/tasks/{$task->id}/toggle");

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => true]);

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/tasks/{$task->id}/toggle");
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => false]);
    }

    /** @test */
    public function it_prevents_access_to_other_users_tasks()
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson("/api/tasks/{$task->id}");
        $response->assertStatus(403);
    }
}

