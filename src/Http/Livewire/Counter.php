<?php

namespace Sndpbag\AdminPanel\Http\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function render()
    {
        return view('admin-panel::livewire.counter');
    }
}
