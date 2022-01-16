<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Profile extends Component
{
    public string $name;

    public string $email;

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email',
    ];

    public function mount(): void
    {
        $user = User::find(auth()->user()->id);
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function render()
    {
        return view('livewire.profile')->extends('layouts.app')->section('content');
    }

    public function save()
    {
        $this->validate();
        $user = User::find(auth()->user()->id);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();
        toastr()->addSuccess(__('Your profile has been updated'));

        return redirect()->route('profile');
    }
}
