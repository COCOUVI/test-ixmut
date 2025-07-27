<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class PerformanceChart extends Component
{
    public $scores = [];

    public function mount($scores)
    {
        $this->scores = collect($scores)->sortByDesc('score')->values();
    }

    public function render()
    {
        return view('livewire.admin.performance-chart');
    }
}
