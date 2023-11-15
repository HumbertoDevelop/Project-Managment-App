<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class AppDashboard extends Component
{
    public $openEdit = false;
    public $openCreate = false;
    public $openTasks = false;
    public $showGIF = true;

    public $tasks;
    public $idProject;
    public $selectedProject;

    public $title;
    public $description;
    public $projects;
    public $user;
    public $search;
    public $project;

    public function getListeners()
    {
        return [
            'cleaningIds',
        ];
    }

    //Cleaning inputs
    public function clearInputs()
    {
        $this->reset('title');
        $this->reset('description');
    }

    //Opening create modal project
    public function openModalCreate()
    {
        $this->openCreate = true;
        //Cleaning inputs
        $this->clearInputs();
        $this->cleaningIds();
    }

    //Closing create modal project
    public function closeModalCreate()
    {
        $this->clearInputs();

        $this->openCreate = false;
    }

    //Opening tasks modal project
    public function openModalTasks($id)
    {

        $this->idProject = $id;
        $this->openTasks = true;
        $this->dispatch('openModalTasks', openTasks: $this->openTasks)->to(TasksSection::class);
        $this->dispatch('thisProject', idProject: $this->idProject)->to(CreateTask::class);
    }

    //Closing edit modal project
    public function closeModalEdit()
    {
        $this->clearInputs();
        $this->cleaningIds();

        $this->openEdit = false;
    }

    //Cleaning ids
    public function cleaningIds()
    {
        $this->reset('selectedProject');
        $this->reset('idProject');
    }

    //Opening edit modal project
    public function openModalEdit($id)
    {
        $this->openEdit = true;
        $this->idProject = $id;
        $this->selectedProject = Project::find($id);
        $this->title = $this->selectedProject->title;
        $this->description = $this->selectedProject->description;
    }

    //Deleting project
    public function deletingProject()
    {
        try {
            // Find the project
            $thisProject = Project::find($this->idProject);

            // Deleting the project
            $this->project = $thisProject->delete();

            // Cleaning inputs
            $this->clearInputs();
            // $this->cleaningArrayTeammates();

            session()->flash('success', 'Project deleted.');

            $this->closeModalEdit();
            $this->cleaningIds();
            $this->mount();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //Edutuning project
    public function editProject()
    {
        try {
            $this->validate(
                [
                    'title' => 'required|string|max:45',
                    'description' => 'required',
                ]
            );

            // Find the project
            $this->project = Project::find($this->idProject);

            // Update the project
            $this->project->update([
                'title' => $this->title,
                'description' => $this->description
            ]);

            // Cleaning inputs
            $this->clearInputs();
            $this->cleaningIds();
            // $this->cleaningArrayTeammates();

            session()->flash('success', 'Project edited.');

            $this->closeModalEdit();
            $this->mount();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //Creating new project
    public function createProject()
    {
        try {
            //Validating inputs project
            $this->validate([
                'title' => 'required|string|max:45',
                'description' => 'required',
            ]);

            //Creating project
            $this->project = Project::create([
                'title' => $this->title,
                'description' => $this->description
            ]);

            //Cleaning inputs
            $this->clearInputs();
            $this->cleaningIds();

            session()->flash('success', 'New project created.');

            $this->closeModalCreate();
            $this->mount();
        } catch (\Exception $e) {
            throw $e;
        }
    }


    //Livewire lifecycle mounting
    public function mount()
    {
        $this->projects = Project::all();
    }

    public function render()
    {
        return view('livewire.app-dashboard');
    }
}
