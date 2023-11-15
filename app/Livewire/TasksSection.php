<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class TasksSection extends Component
{
    public $openModalTask;
    public $tasks;
    public $editingTaskId = null;
    public $badges = array();

    // Define listeners for various events
    protected $listeners = [
        'taskAdded' => 'refreshTasks',
        'taskEdited' => 'taskEdited',
        'openModalTasks' => 'openModalTasks',
        'closeModalTasks' => 'closeModalTasks',
    ];

    // Handle task edited event
    public function taskEdited()
    {
        session()->flash('messageEvent', 'Task edited successfully.');
        $this->refreshTasks();
    }

    // Handle task added event
    public function taskAdded()
    {
        session()->flash('messageEvent', 'Task added successfully.');
        $this->refreshTasks();
    }

    // Handle close modal event
    public function closeModalTasks()
    {
        $this->openModalTask = false;
    }

    // Handle open modal event
    public function openModalTasks($openTasks)
    {
        $this->openModalTask = $openTasks;
    }

    // Delete a task
    public function delete(Task $task): void
    {
        $task->delete();
        $this->refreshTasks();
    }

    // Edit a task
    public function edit($task): void
    {
        $this->editingTaskId = $task['id'];
    }

    // Refresh tasks
    public function mount()
    {
        $this->refreshTasks();
    }

    // Refresh tasks
    public function refreshTasks()
    {
        // Get updated tasks
        $this->tasks = Task::with('users')->get();
    }

    // Cancel editing a task
    public function cancelEdit(): void
    {
        $this->editingTaskId = null;
    }

    // Render the view
    public function render()
    {
        return view('livewire.tasks-section');
    }
}
