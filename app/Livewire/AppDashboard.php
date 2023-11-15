<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class AppDashboard extends Component
{
    // Declare public variables
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

    // Define listeners for various events
    public function getListeners()
    {
        return [
            'cleaningIds',
        ];
    }

    // Function to clear input fields
    public function clearInputs()
    {
        $this->reset('title');
        $this->reset('description');
    }

    // Function to open the create modal
    public function openModalCreate()
    {
        $this->openCreate = true;
        // Clear input fields
        $this->clearInputs();
        $this->cleaningIds();
    }

    // Function to close the create modal
    public function closeModalCreate()
    {
        // Clear input fields
        $this->clearInputs();

        $this->openCreate = false;
    }

    // Function to open the tasks modal
    public function openModalTasks($id)
    {
        $this->idProject = $id;
        $this->openTasks = true;
        // Dispatch events to open tasks and set the current project
        $this->dispatch('openModalTasks', openTasks: $this->openTasks)->to(TasksSection::class);
        $this->dispatch('thisProject', idProject: $this->idProject)->to(CreateTask::class);
    }

    // Function to close the edit modal
    public function closeModalEdit()
    {
        // Clear input fields and IDs
        $this->clearInputs();
        $this->cleaningIds();

        $this->openEdit = false;
    }

    // Function to clear IDs
    public function cleaningIds()
    {
        $this->reset('selectedProject');
        $this->reset('idProject');
    }

    // Function to open the edit modal
    public function openModalEdit($id)
    {
        $this->openEdit = true;
        $this->idProject = $id;
        // Find the selected project and set the title and description
        $this->selectedProject = Project::find($id);
        $this->title = $this->selectedProject->title;
        $this->description = $this->selectedProject->description;
    }

    // Function to delete a project
    public function deletingProject()
    {
        try {
            // Find the project
            $thisProject = Project::find($this->idProject);

            // Deleting the project
            $this->project = $thisProject->delete();

            // Cleaning inputs
            $this->clearInputs();

            session()->flash('success', 'Project deleted.');

            $this->closeModalEdit();
            $this->cleaningIds();
            $this->mount();
        } catch (\Exception $e) {
            throw $e;
        }
    }
    // Function to edit a project
    public function editProject()
    {
        try {
            // Validate the input fields
            $this->validate([
                'title' => 'required|string|max:45',
                'description' => 'required',
            ]);

            // Find the project
            $this->project = Project::find($this->idProject);

            // Update the project
            $this->project->update([
                'title' => $this->title,
                'description' => $this->description
            ]);

            // Clear input fields and IDs
            $this->clearInputs();
            $this->cleaningIds();

            session()->flash('success', 'Project edited.');

            $this->closeModalEdit();
            $this->mount();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Function to create a new project
    public function createProject()
    {
        try {
            // Validate the input fields
            $this->validate([
                'title' => 'required|string|max:45',
                'description' => 'required',
            ]);

            // Create the project
            $this->project = Project::create([
                'title' => $this->title,
                'description' => $this->description
            ]);

            // Clear input fields and IDs
            $this->clearInputs();
            $this->cleaningIds();

            session()->flash('success', 'New project created.');

            $this->closeModalCreate();
            $this->mount();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Livewire lifecycle mounting
    public function mount()
    {
        $this->projects = Project::all();
    }

    // Render the view
    public function render()
    {
        return view('livewire.app-dashboard');
    }
}
