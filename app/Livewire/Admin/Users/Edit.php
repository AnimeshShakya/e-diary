<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Edit extends Component
{
    public ?User $user = null;

    /**
     * @var array{name:string,email:string,password:?string,new_password:?string,password_confirmation:?string,role:string}
     */
    public array $form = [
        'name' => '',
        'email' => '',
        'password' => '',
        'new_password' => null,
        'password_confirmation' => null,
        'role' => 'user',
    ];

    public function mount(?User $user = null): void
    {
        $authUser = Auth::user();
        $targetUser = $user ?? $authUser;

        if ($user && $authUser && ! $this->isAdminUser($authUser) && $authUser->id !== $user->id) {
            abort(403);
        }

        if (! $targetUser instanceof User) {
            abort(404);
        }

        $this->user = $targetUser;
        $this->form = [
            'name' => $targetUser->name,
            'email' => $targetUser->email,
            'password' => '',
            'new_password' => null,
            'password_confirmation' => null,
            'role' => $targetUser->role ?? 'user',
        ];
    }

    protected function rules(): array
    {
        $isAdmin = $this->isAdminUser(Auth::user());

        if ($isAdmin) {
            return [
                'form.name' => ['required', 'string', 'max:255'],
                'form.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
                'form.password' => ['required_with:form.new_password', 'string'],
                'form.new_password' => ['nullable', 'string', 'min:8', 'same:form.password_confirmation'],
                'form.password_confirmation' => ['nullable', 'string', 'min:8'],
                'form.role' => ['required', Rule::in(['user', 'admin', 'super_admin'])],
            ];
        }

        return [
            'form.password' => ['required_with:form.new_password', 'string'],
            'form.new_password' => ['nullable', 'string', 'min:8', 'same:form.password_confirmation'],
            'form.password_confirmation' => ['nullable', 'string', 'min:8'],
        ];
    }

    public function save(): mixed
    {
        // Validate fields first
        $this->validate();

        $user = $this->user;
        if ($this->isAdminUser(Auth::user())) {
            $user->name = $this->form['name'];
            $user->email = $this->form['email'];
            $user->role = $this->form['role'];
        }
        if (! empty($this->form['new_password'])) {
            if (! Hash::check((string) ($this->form['password'] ?? ''), (string) $this->user->password)) {
                throw ValidationException::withMessages([
                    'form.password' => 'Current password is incorrect.',
                ]);
            }

            $user->password = Hash::make((string) $this->form['new_password']);
        }
        $user->save();

        session()->flash('status', 'User updated');

        if ($this->isAdminUser(Auth::user())) {
            return to_route('admin.users.index');
        }

        return to_route('settings.password');
    }

    private function isAdminUser(?User $user): bool
    {
        return $user instanceof User && in_array($user->role, ['admin', 'super_admin'], true);
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
