<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateTask extends Component
{
    protected $listeners = ["thisProject"];
    public $title;
    public $due_date;
    public $description;
    public $completed;
    public $task;
    public $badges = array();
    public $users;
    public $user;
    public $search;
    public $project;

    //Open modal event
    public function thisProject($idProject)
    {
        $this->project = $idProject;
    }

    //Deleting collaborators from project
    public function deletedTeammate($id)
    {
        // Removing collaborators from array
        $this->badges = array_filter($this->badges, function ($user) use ($id) {
            return $user[0]->id !== $id;
        });
    }

    //Searching and adding collaborators to project
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


    //Cleaning array
    public function cleaningArrayTeammates()
    {
        array_splice($this->badges, 0, count($this->badges));
    }

    public function addTask()
    {
        $this->validate([
            'title' => 'required|unique:tasks,title',
            'description' => 'required',
            'due_date' => 'required',
        ]);

        if ($this->completed === null) {
            $this->completed = 0;
        } else {
            $this->completed = 1;
        }

        $task = Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'completed' => $this->completed,
        ]);

        if (count($this->badges) !== 0) {
            $userIds = array_map(function ($user) {
                return $user[0]->id;
            }, $this->badges);

            $task->users()->sync($userIds);
        }
        // Asignar rol a los usuarios
        foreach ($this->badges as $badge) {
            $user = $badge[0];
            $user->assignRole('collaborator');
        }
        // Asociar la tarea a un proyecto
        $project = Project::find($this->project);
        $project->tasks()->attach($task);

        $this->cleaningInputs();
        $this->dispatch('taskAdded')->to(TasksSection::class);
        // $this->dispatch('taskModalClose')->to(TasksSection::class);
    }

    //cleaning inputs
    public function cleaningInputs()
    {
        $this->reset('description');
        $this->reset('title');
        $this->reset('due_date');
        $this->reset('completed');
        $this->reset('badges');
        $this->reset('search');
    }


    public function render()
    {
        return view('livewire.create-task');
    }
}
