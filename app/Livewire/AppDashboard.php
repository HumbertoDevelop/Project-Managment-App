<?php

namespace App\Livewire;

use Livewire\Component;

class AppDashboard extends Component
{
    public $projects = array();
    public $open = false;

    public function closeModal()
    {
        $this->open = false;
    }
    
    public function openModal()
    {
        $this->open = true;

    }

    public function createProject()
    {
        
    }

    public function mount()
    {
        $this->projects;
    }

    public function render()
    {
        return view('livewire.app-dashboard');
    }
}
