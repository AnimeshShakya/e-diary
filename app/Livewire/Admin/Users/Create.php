<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Create extends Component
{
    /**
     * @var array{name:string,email:string,password:?string,password_confirmation:?string,role:string}
     */
    public array $form = [
        'name' => '',
        'email' => '',
        'password' => null,
        'password_confirmation' => null,
        'role' => 'user',
    ];

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'form.password' => ['required', 'string', 'min:8', 'confirmed'],
            'form.password_confirmation' => ['required', 'string', 'min:8'],
            'form.role' => ['required', Rule::in(['user', 'admin', 'super_admin'])],
        ];
    }

    public function save(): RedirectResponse
    {
        $this->validate();
        User::create([
            'name' => $this->form['name'],
            'email' => $this->form['email'],
            'password' => Hash::make((string) $this->form['password']),
            'role' => $this->form['role'],
        ]);

        session()->flash('status', 'User created');

        return redirect()->route('admin.users.index');
    }

    public function render(): View
    {
        return view('livewire.admin.users.create');
    }

    public function layoutData(): array
    {
        return [
            'title' => 'New User',
            'header' => 'Create User',
        ];
    }
}
