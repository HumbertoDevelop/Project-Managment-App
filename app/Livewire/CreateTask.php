<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Livewire\Component;

class CreateTask extends Component
{
    public $title;
    public $due_date;
    public $description;
    public $completed;
    public $search;
    public $task;
    public $badges = [];
    public $project;

    // Define listeners for various events
    protected $listeners = [
        'thisProject' => 'thisProject',
        'taskAdded' => 'refreshTasks',
        'taskEdited' => 'taskEdited',
        'openModalTasks' => 'openModalTasks',
        'closeModalTasks' => 'closeModalTasks',
    ];

    // Open modal event
    public function thisProject($idProject)
    {
        $this->project = $idProject;
    }

    // Deleting collaborators from project
    public function deletedTeammate($id)
    {
        $this->badges = array_filter($this->badges, fn($user) => $user[0]->id !== $id);
    }

    // Searching and adding collaborators to project
    public function searchDB()
    {
        $user = User::where('email', $this->search)->first();

        if ($user) {
            $this->badges[] = [$user];
        } else {
            session()->flash('userNotFound', 'User not found in database');
        }
    }

    // Add a new task
    public function addTask()
    {
        $this->validate([
            'title' => 'required|unique:tasks,title',
            'description' => 'required',
            'due_date' => 'required',
        ]);

        $this->completed = $this->completed ? 1 : 0;

        $task = Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'completed' => $this->completed,
        ]);

        if (!empty($this->badges)) {
            $userIds = array_map(fn($user) => $user[0]->id, $this->badges);
            $task->users()->sync($userIds);
        }

        foreach ($this->badges as $badge) {
            $badge[0]->assignRole('collaborator');
        }

        Project::find($this->project)->tasks()->attach($task);

        $this->reset(['description', 'title', 'due_date', 'completed', 'badges', 'search']);

        $this->emit('taskAdded');
    }

    // Render the view
    public function render()
    {
        return view('livewire.create-task');
    }
}
