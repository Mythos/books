<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Profile extends Component
{
    public User $user;

    protected $rules = [
        'user.name' => 'required',
        'user.email' => 'required|email',
        'user.format_isbns_enabled' => 'boolean',
        'user.date_format' => 'required',
    ];

    public function mount(): void
    {
        $this->user = User::find(auth()->user()->id);
    }

    public function render()
    {
        return view('livewire.profile')->extends('layouts.app')->section('content');
    }

    public function save()
    {
        $this->validate();
        $this->user->save();
        toastr()->addSuccess(__('Your profile has been updated'));

        return redirect()->route('profile');
    }
}
