<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Contracts\View\View;

#[Layout('layouts.admin')]
class Edit extends Component
{
    public User $user;

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

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->form = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => null,
            'password_confirmation' => null,
            'role' => $user->role ?? 'user',
        ];
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
            'form.password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'form.password_confirmation' => ['nullable', 'string', 'min:8'],
            'form.role' => ['required', Rule::in(['user', 'admin', 'super_admin'])],
        ];
    }

    public function save(): \Illuminate\Http\RedirectResponse
    {
        $this->validate();

        $user = $this->user;
        $user->name = $this->form['name'];
        $user->email = $this->form['email'];
        $user->role = $this->form['role'];
        if (! empty($this->form['password'])) {
            $user->password = Hash::make((string) $this->form['password']);
        }
        $user->save();

        session()->flash('status', 'User updated');

        return redirect()->route('admin.users.index');
    }

    public function render(): View
    {
        return view('livewire.admin.users.edit');
    }

    public function layoutData(): array
    {
        return [
            'title' => 'Edit User',
            'header' => 'Edit User',
        ];
    }
}
