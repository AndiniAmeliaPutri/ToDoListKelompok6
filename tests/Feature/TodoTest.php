<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_todo_index(): void
    {
        Todo::create([
            'title' => 'Tugas Pertama',
            'priority' => 'high',
            'is_completed' => false,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Tugas Pertama');
    }

    public function test_can_create_todo(): void
    {
        $response = $this->post('/todos', [
            'title' => 'Belajar Laravel 13',
            'description' => 'Mempelajari routing dan blade templates',
            'priority' => 'high',
            'due_date' => now()->addDays(2)->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('todos', [
            'title' => 'Belajar Laravel 13',
            'priority' => 'high',
        ]);
    }

    public function test_can_toggle_todo_status(): void
    {
        $todo = Todo::create([
            'title' => 'Tugas Belum Selesai',
            'priority' => 'medium',
            'is_completed' => false,
        ]);

        $response = $this->patch("/todos/{$todo->id}/toggle");
        $response->assertRedirect();

        $this->assertTrue($todo->fresh()->is_completed);
        $this->assertNotNull($todo->fresh()->completed_at);

        // Toggle back
        $this->patch("/todos/{$todo->id}/toggle");
        $this->assertFalse($todo->fresh()->is_completed);
        $this->assertNull($todo->fresh()->completed_at);
    }

    public function test_can_update_todo(): void
    {
        $todo = Todo::create([
            'title' => 'Judul Lama',
            'priority' => 'low',
            'is_completed' => false,
        ]);

        $response = $this->put("/todos/{$todo->id}", [
            'title' => 'Judul Baru yang Diedit',
            'description' => 'Deskripsi baru',
            'priority' => 'high',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Judul Baru yang Diedit',
            'priority' => 'high',
        ]);
    }

    public function test_can_delete_todo(): void
    {
        $todo = Todo::create([
            'title' => 'Tugas yang akan dihapus',
            'priority' => 'low',
        ]);

        $response = $this->delete("/todos/{$todo->id}");
        $response->assertRedirect();

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    public function test_filter_and_search(): void
    {
        Todo::create([
            'title' => 'Membuat API Autentikasi',
            'priority' => 'high',
            'is_completed' => false,
        ]);

        Todo::create([
            'title' => 'Merapikan CSS Layout',
            'priority' => 'low',
            'is_completed' => true,
        ]);

        // Search
        $response = $this->get('/?search=Autentikasi');
        $response->assertSee('Membuat API Autentikasi');
        $response->assertDontSee('Merapikan CSS Layout');

        // Status completed
        $responseCompleted = $this->get('/?status=completed');
        $responseCompleted->assertSee('Merapikan CSS Layout');
        $responseCompleted->assertDontSee('Membuat API Autentikasi');

        // Priority filter
        $responsePriority = $this->get('/?priority=high');
        $responsePriority->assertSee('Membuat API Autentikasi');
        $responsePriority->assertDontSee('Merapikan CSS Layout');
    }
}
