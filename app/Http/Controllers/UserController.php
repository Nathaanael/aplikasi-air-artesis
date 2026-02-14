<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Warga;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
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
            'alamat' => 'required',
            'nomor_pelanggan' => 'required'
        ]);

        DB::transaction(function () use ($r) {

            $user = User::create([
                'username' => $r->username,
                'password' => Hash::make($r->password),
                'role' => 'warga',
            ]);

            Warga::insertWithShift(
                (int)$r->nomor_pelanggan,
                [
                    'user_id' => $user->id,
                    'nama' => $r->nama,
                    'rt' => $r->rt,
                    'rw' => $r->rw,
                    'alamat' => $r->alamat,
                ]
            );
        });

        return back()->with('success','User dibuat + nomor disisipkan');
    }



    // Tampilkan daftar user warga
    public function index(Request $request)
    {
        $q = $request->q;

        $users = User::query()
            ->select('users.*')
            ->join('warga', 'warga.user_id', '=', 'users.id')
            ->where('users.role', 'warga')

            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('users.username', 'like', "%$q%")
                        ->orWhere('warga.nama', 'like', "%$q%")
                        ->orWhere('warga.alamat', 'like', "%$q%");
                });
            })

            ->orderByRaw('CAST(warga.nomor_pelanggan AS UNSIGNED)')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('users.partials.table', compact('users'))->render();
        }

        return view('users.index', compact('users','q'));
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
            'alamat' => 'required',
            'nomor_pelanggan' => 'required|unique:warga,nomor_pelanggan,'.$user->warga->id,
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
            'nomor_pelanggan' => $r->nomor_pelanggan,
        ]);

        return redirect()->route('users.index')->with('success','User berhasil diupdate');
    }

    // Hapus user
    public function destroy(User $user)
    {
        $user->warga->deleteWithShift();
        $user->delete();

        return back()->with('success','User dihapus + nomor dirapatkan');
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

