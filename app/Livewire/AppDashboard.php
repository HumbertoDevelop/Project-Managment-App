<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AppDashboard extends Component
{
    public $openEdit = false;
    public $idProject;
    public $selectedProject;
    public $badges = array();

    public $title;
    public $description;
    public $projects;
    public $openCreate = false;
    public $openTasks = false;
    public $user;
    public $search;
    public $project;

    //Cleaning inputs
    public function clearInputs()
    {
        $this->reset('title');
        $this->reset('description');
    }

    //Closing create modal project
    public function closeModalCreate()
    {
        $this->clearInputs();

        $this->openCreate = false;
    }

    //Opening create modal project
    public function openModalTasks()
    {
        $this->openTasks = true;
    }

    //Opening create modal project
    public function openModalCreate()
    {
        $this->openCreate = true;
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

        $users = $this->selectedProject->users()->get();

        $this->badges = $users->map(function ($user) {
            return [$user];
        })->toArray();
    }

    //Deleting collaborators from project
    public function deletedTeammate($id)
    {
        //Removing collaborators from array
        $this->badges = array_filter($this->badges, function ($user) use ($id) {
            return $user[0]->id !== $id;
        });
    }

    //Searching and adding collaborators to project
    public function searchDB()
    {
        try {
            $this->user = DB::table('users')
                ->select('id', 'name', 'email')
                ->where('email', '=', $this->search)
                ->get();

            if (count($this->user) !== 0) {
                //Adding collaborators to array
                array_push($this->badges, $this->user);
            } else {
                //Message error from database
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
            $this->cleaningArrayTeammates();

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

            // If there's teammates in project save on relation table
            if (count($this->badges) !== 0) {
                $userIds = array_map(function ($user) {
                    return $user[0]->id;
                }, $this->badges);

                $this->project->users()->sync($userIds);
            } else {
                $this->project->users()->detach();
            }

            // Cleaning inputs
            $this->clearInputs();
            $this->cleaningIds();
            $this->cleaningArrayTeammates();

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

            //If there are teammates in the project, save them in the relation table
            if (count($this->badges) !== 0) {
                $userIds = array_map(function ($user) {
                    return $user[0]->id;
                }, $this->badges);

                $this->project->users()->attach($userIds);
            }

            //Cleaning inputs
            $this->clearInputs();
            $this->cleaningIds();
            $this->cleaningArrayTeammates();

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
        $this->projects = DB::table('projects')->select('id', 'title', 'description')->get();
    }

    public function render()
    {
        return view('livewire.app-dashboard');
    }
}
