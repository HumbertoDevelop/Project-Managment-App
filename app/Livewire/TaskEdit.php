<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Livewire\Component;

class TaskEdit extends Component
{
    public $description;
    public $title;
    public $due_date;
    public $completed;
    public $search;
    public $task;
    public $badges = [];

    // Mount function to initialize task data
    public function mount(int $taskId): void
    {
        $task = Task::findOrFail($taskId);

        $this->task = $taskId;
        $this->description = $task->description;
        $this->title = $task->title;
        $this->due_date = $task->due_date;
        $this->completed = $task->completed;

        $users = $task->users()->get();

        foreach ($users as $user) {
            array_push($this->badges, [$user]);
        }
    }

    // Searching and adding collaborators to project
    public function searchDB()
    {
        try {
            $user = User::where('email', '=', $this->search)->first();

            if ($user) {
                // Agregar colaborador al array
                array_push($this->badges, [$user]);
            } else {
                // Mostrar mensaje de error
                session()->flash('userNotFound', 'User not found in database');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Function to update task
    public function updateTask(): void
    {
        // Validate the request data
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'due_date' => 'required',
        ]);

        // Check if task is completed
        $this->completed = $this->completed ? 1 : 0;

        // Find the task by ID and update it
        $task = Task::findOrFail($this->task);
        $task->update([
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'completed' => $this->completed,
        ]);

        // Get the user IDs from the badges array
        $userIds = collect($this->badges)->pluck(0)->toArray();

        // Sync the users associated with the task
        $task->users()->sync($userIds);
    }

    // Deleting collaborators from project
    public function deletedTeammate($id)
    {
        // Removing collaborators from array
        $this->badges = array_filter($this->badges, function ($user) use ($id) {
            return $user[0]->id !== $id;
        });
    }

    // Function to cancel task
    public function cancel(): void
    {
        $this->dispatch('task-edit-canceled')->to(TasksSection::class);
    }

    // Render the view
    public function render()
    {
        return view('livewire.task-edit');
    }
}
