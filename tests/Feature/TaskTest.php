<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $taskRepository;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new TaskRepository();
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

   #[Test]
    public function it_can_get_all_tasks_for_authenticated_user()
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);
        Task::factory()->count(2)->create(); // Tasks for other users

        $tasks = $this->taskRepository->GetAll();

        $this->assertCount(3, $tasks);
        $tasks->each(function ($task) {
            $this->assertEquals($this->user->id, $task->user_id);
        });
    }

     #[Test]
    public function it_can_get_a_task_by_id()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $foundTask = $this->taskRepository->GetById($task->id);

        $this->assertEquals($task->id, $foundTask->id);
        $this->assertEquals($task->title, $foundTask->title);
    }

     #[Test]
    public function it_throws_exception_when_getting_non_existent_task_by_id()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->taskRepository->GetById("non-existent-id");
    }

     #[Test]
    public function it_can_create_a_new_task()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'Description for new task',
            'status' => 'pending',
        ];

        $task = $this->taskRepository->Create($taskData);

        $this->assertNotNull($task);
        $this->assertEquals($taskData['title'], $task->title);
        $this->assertEquals($this->user->id, $task->user_id);
        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $this->user->id,
        ]);
    }

     #[Test]
    public function it_can_edit_an_existing_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $newData = [
            'title' => 'Updated Task Title',
            'status' => 'completed',
        ];

        $updatedTask = $this->taskRepository->Edit($task->id, $newData);

        $this->assertEquals($newData['title'], $updatedTask->title);
        $this->assertEquals($newData['status'], $updatedTask->status);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => 'completed',
        ]);
    }

    #[Test]
    public function it_throws_exception_when_editing_non_existent_task()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->taskRepository->Edit("non-existent-id", ['title' => 'test']);
    }

    #[Test]
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $deletedTask = $this->taskRepository->Delete($task->id);

        $this->assertEquals($task->id, $deletedTask->id);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]); // Assuming soft deletes are used
    }

     #[Test]
    public function it_throws_exception_when_deleting_non_existent_task()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->taskRepository->Delete("non-existent-id");
    }

     #[Test]
    public function it_can_filter_tasks_by_status()
    {
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'pending']);
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'completed']);
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'pending']);
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'in_progress']);
        Task::factory()->create(['status' => 'pending']); // Other user's task

        $pendingTasks = $this->taskRepository->filterBystatus('pending');

        $this->assertCount(2, $pendingTasks);
        $pendingTasks->each(function ($task) {
            $this->assertEquals('pending', $task->status);
            $this->assertEquals($this->user->id, $task->user_id);
        });

        $completedTasks = $this->taskRepository->filterBystatus('completed');
        $this->assertCount(1, $completedTasks);
        $this->assertEquals('completed', $completedTasks->first()->status);
    }
}


