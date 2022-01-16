<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ChangePassword extends Component
{
    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    protected $rules = [
        'current_password' => 'required|current_password',
        'new_password' => 'required|string|min:8|confirmed',
        'new_password_confirmation' => 'required|string|min:8',
    ];

    public function render()
    {
        return view('livewire.change-password')->extends('layouts.app')->section('content');
    }

    public function save()
    {
        $this->validate();

        $user = User::find(auth()->user()->id);
        $user->password = Hash::make($this->new_password);
        $user->save();

        toastr()->addSuccess(__('Your password has been changed'));

        return redirect()->route('profile');
    }
}
