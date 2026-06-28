<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('pos.user_management', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'is_admin' => 'required|in:0,1',
        ]);

        $this->userService->createUser([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_admin' => (bool)$validated['is_admin'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'nullable|string|min:8',
            'is_admin' => 'required|in:0,1',
        ]);

        $this->userService->updateUser($id, [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ?? null,
            'is_admin' => (bool)$validated['is_admin'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $currentUserId = (int) (Auth::id() ?? 0);
        if ((int)$id === $currentUserId) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account!');
        }

        $this->userService->deleteUser($id);

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }
}