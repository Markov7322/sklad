<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('skladchinas')->paginate();
        return view('admin.users.index', compact('users'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'password' => 'nullable|string|min:6',
            'banned' => 'boolean',
        ]);

        $data['banned'] = $request->boolean('banned');

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function toggleBan(User $user)
    {
        $user->banned = ! $user->banned;
        $user->save();

        return back();
    }

    public function participations(User $user)
    {
        $skladchinas = $user->skladchinas()->with('category')->get();

        return view('admin.users.participations', compact('user', 'skladchinas'));
    }
}
