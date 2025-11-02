<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public bool $showModal = false;

    public ?int $editId = null;

    /**
     * @var array{name:string,email:string,password:?string,role:string}
     */
    public array $form = [
        'name' => '',
        'email' => '',
        'password' => null,
        'role' => 'user',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->editId = null;
        $this->showModal = true;
    }

    public function edit(User $user): void
    {
        $this->editId = $user->id;
        $this->form = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => null,
            'role' => $user->role ?? 'user',
        ];
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate($this->rules());

        if ($this->editId) {
            $user = User::findOrFail($this->editId);
            $user->name = $this->form['name'];
            $user->email = $this->form['email'];
            $user->role = $this->form['role'];
            if (! empty($this->form['password'])) {
                $user->password = Hash::make($this->form['password']);
            }
            $user->save();
        } else {
            User::create([
                'name' => $this->form['name'],
                'email' => $this->form['email'],
                'password' => Hash::make((string) $this->form['password']),
                'role' => $this->form['role'],
            ]);
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('notify', message: 'User saved');
    }

    public function delete(User $user): void
    {
        // Prevent deleting yourself
        if (\Illuminate\Support\Facades\Auth::id() === $user->id) {
            $this->dispatch('notify', message: "You can't delete yourself.");

            return;
        }

        $user->delete();
        $this->dispatch('notify', message: 'User deleted');
    }

    protected function rules(): array
    {
        $roleValues = ['user', 'admin', 'super_admin'];

        $base = [
            'form.name' => ['required', 'string', 'max:255'],
            'form.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editId)],
            'form.role' => ['required', Rule::in($roleValues)],
        ];

        if ($this->editId) {
            $base['form.password'] = ['nullable', 'string', 'min:8'];
        } else {
            $base['form.password'] = ['required', 'string', 'min:8'];
        }

        return $base;
    }

    protected function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'email' => '',
            'password' => null,
            'role' => 'user',
        ];
    }

    public function render(): View
    {
        /** @var LengthAwarePaginator $users */
        $users = User::query()
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        return view('livewire.admin.users.index', [
            'users' => $users,
        ]);
    }

    /**
     * Provide data to the admin layout.
     *
     * @return array{title:string,header:string}
     */
    public function layoutData(): array
    {
        return [
            'title' => 'Users',
            'header' => 'Manage Users',
        ];
    }
}
