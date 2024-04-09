<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all tasks
     *
     * @return void
     */
    public function test_index_tasks(): void
    {
        $user = User::factory()->create();

        Task::factory()->count(3)->for($user)->create();

        $response = $this->actingAs($user)->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJson(['data' => [], 'meta' => []])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test storing one task
     *
     * @return void
     */
    public function test_store_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/tasks', [
            'title' => 'test title',
            'description' => 'test description',
        ]);

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    /**
     * Test show one task
     *
     * @return void
     */
    public function test_show_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->for($user)->create();

        $response = $this->actingAs($user)->getJson('/api/v1/tasks/' . $task->id);

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    /**
     * Test update one task
     *
     * @return void
     */
    public function test_update_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->for($user)->create();

        $response = $this->actingAs($user)->putJson('/api/v1/tasks/' . $task->id, [
            'title' => 'updated title',
            'status' => TaskStatus::COMPLETED,
        ]);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->where('data.status', TaskStatus::COMPLETED->value)
                    ->etc()
            );
    }

    /**
     * Test destroy one task
     *
     * @return void
     */
    public function test_destroy_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete('/api/v1/tasks/' . $task->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => [],
            ]);
    }
}
