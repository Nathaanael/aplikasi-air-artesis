<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Warga;

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
            'nama' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'alamat' => 'required'
        ]);

        $user = User::create([
            'username' => $r->username,
            'password' => Hash::make($r->password),
            'role' => 'warga',
        ]);
        $last = Warga::orderBy('id', 'desc')->first();
        $lastNumber = $last ? (int)$last->nomor_pelanggan : 0;
        $nomorPelanggan = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        Warga::create([
            'user_id' => $user->id,
            'nama' => $r->nama,
            'rt' => $r->rt,
            'rw' => $r->rw,
            'alamat' => $r->alamat,
            'nomor_pelanggan' => $nomorPelanggan,
        ]);


        return redirect()->route('users.index')->with('success','User berhasil dibuat');
    }

    // Tampilkan daftar user warga
    public function index(Request $request)
    {
        $q = $request->q;

        $users = User::where('role', 'warga')
            ->where('role', 'warga')
                ->where(function ($query) use ($q) {
                    $query->where('username', 'like', "%$q%")
                        ->orWhereHas('warga', function ($w) use ($q) {
                            $w->where('nama', 'like', "%$q%")
                                ->orWhere('alamat', 'like', "%$q%");
                        });
                })

            ->orderBy('username')
            ->paginate(10)
            ->withQueryString();

        // 👉 kalau AJAX → hanya render tabel
        if ($request->ajax()) {
            return view('users.partials.table', compact('users'))->render();
        }

        return view('users.index', compact('users', 'q'));
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
            'nama' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'alamat' => 'required'
        ]);

        $user->update([
            'username' => $r->username,
            'password' => $r->password ? Hash::make($r->password) : $user->password,
        ]);

        // Update data warga
        $user->warga()->update([
            'nama' => $r->nama,
            'rt' => $r->rt,
            'rw' => $r->rw,
            'alamat' => $r->alamat,
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

