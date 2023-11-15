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
                // Add collaborator to the array
                array_push($this->badges, [$user]);
            } else {
                // Show error message
                session()->flash('userNotFound', 'User not found in database');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //Cleaning inputs
    public function clearInputs()
    {
        $this->reset('title');
        $this->reset('description');
        $this->reset('due_date');
        $this->reset('completed');
    }

    //Cleaning ids
    public function clearIds()
    {
        $this->reset('task');
    }

    // Function to update task
    public function updateTask(int $taskId): void
    {
        // Validate the request data
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'due_date' => 'required',
        ]);

        // Check if task is completed
        $this->completed = $this->completed ? 1 : 0;

        // Find the project
        $this->task = Task::find($taskId);
        //Update task
        $this->task->update([
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'completed' => $this->completed,
        ]);

        // Get the user IDs from the badges array
        $userIds = collect($this->badges)->pluck('0.id')->toArray();
        // Sync the users associated with the task
        $this->task->users()->sync($userIds);

        $this->dispatch('taskEdited')->to(TasksSection::class);
        $this->clearInputs();
        $this->clearIds();
        $this->cancel();
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
