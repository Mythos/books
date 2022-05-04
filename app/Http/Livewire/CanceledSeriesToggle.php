<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CanceledSeriesToggle extends Component
{
    public bool $show_canceled_series;

    public function render()
    {
        $this->show_canceled_series = session('show_canceled_series') ?? false;

        return view('livewire.canceled-series-toggle');
    }

    public function toggle()
    {
        $this->show_canceled_series = !$this->show_canceled_series;
        session()->put('show_canceled_series', $this->show_canceled_series);

        return redirect(request()->header('Referer'));
    }
}
