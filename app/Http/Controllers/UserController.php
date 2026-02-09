<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Tampilkan form create
    public function create()
    {
        return view('users.create');
    }

    // Simpan user baru
    public function store(Request $r)
    {
        $r->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:4',
        ]);

        User::create([
            'username' => $r->username,
            'password' => Hash::make($r->password),
            'role' => 'warga',
        ]);

        return redirect()->route('users.index')->with('success','User berhasil dibuat');
    }

    // Tampilkan daftar user warga
    public function index()
    {
        $users = User::where('role','warga')->with('warga')->orderBy('username')->get();
        return view('users.index', compact('users'));
    }

    // Tampilkan form edit user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Update user
    public function update(Request $r, User $user)
    {
        $r->validate([
            'username' => 'required|unique:users,username,'.$user->id,
            'password' => 'nullable',
        ]);

        $user->update([
            'username' => $r->username,
            'password' => $r->password ? Hash::make($r->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success','User berhasil diupdate');
    }

    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success','User berhasil dihapus');
    }
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:4|max:20'
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success','Password berhasil direset');
    }
}

