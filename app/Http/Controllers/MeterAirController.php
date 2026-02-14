<?php

namespace App\Http\Controllers;

use App\Models\MeterAir;
use App\Models\User;
use Illuminate\Http\Request;


class MeterAirController extends Controller
{
    private const ABONEMEN = 5000;
    private const TARIF_PER_M3 = 2000;

    public function create(Request $request)
    {
        $bulan = $request->bulan ?? date('n');
        $tahun = $request->tahun ?? date('Y');

        $users = User::where('role', 'warga')->orderBy('username')->with('warga')->get();


        return view('air.create', compact('users','bulan','tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'stand_kini' => 'required|numeric|min:0',
            'tagihan_bulan_lalu' => 'nullable|numeric'
        ]);

        $exists = MeterAir::where('user_id', $request->user_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Data meter air untuk warga ini pada bulan & tahun tersebut sudah ada.');
        }

        $prev = MeterAir::prevRecord(
            $request->user_id,
            $request->bulan,
            $request->tahun
        );

        $standLama = $prev?->stand_kini ?? 0;
        $pemakaian = $request->stand_kini - $standLama;

        if ($pemakaian < 0) {
            return back()->withErrors([
                'stand_kini' => 'Stand kini tidak boleh lebih kecil dari stand lama'
            ]);
        }
        $hutang = $request->tagihan_bulan_lalu ?? 0;

        if ($hutang > 0) {
            $hutang = -1 * $hutang;
        }
        MeterAir::create([
            'user_id' => $request->user_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'stand_lama' => $standLama,
            'stand_kini' => $request->stand_kini,
            'pemakaian' => $pemakaian,
            'tagihan_bulan_lalu' => $hutang,
        ]);

        return redirect()->route('air.index')
            ->with('success', 'Data meter berhasil disimpan');
    }




    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('n');
        $tahun = $request->tahun ?? date('Y');
        $search = $request->search;

        $query = MeterAir::with('user.warga')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                ->orWhereHas('warga', function ($w) use ($search) {
                    $w->where('nama', 'like', "%$search%");
                });
            });
        }

        $data = $query->orderBy('user_id')
                    ->paginate(5)
                    ->withQueryString();

        if ($request->ajax()) {
            return view('air.partials.cards', compact('data'))->render();
        }

        return view('air.index', compact('data','bulan','tahun'));
    }

    public function indexWarga(Request $request)
    {
        $bulan = $request->bulan ?? date('n');
        $tahun = $request->tahun ?? date('Y');

        $data = MeterAir::with('user')
            ->where('user_id', auth()->id())
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->paginate(5);

        if ($request->ajax()) {
            return view('warga.partials.cards', compact('data'))->render();
        }

        return view('warga.index', compact('data','bulan','tahun'));
    }

    public function edit(MeterAir $meter)
    {
        $users = User::where('role','warga')->orderBy('username')->get();
        $meters = MeterAir::with('user')->get();
        return view('air.edit', compact('meter','users'));
    }

    public function update(Request $request, MeterAir $meter)
    {
        $request->validate([
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
            'stand_kini' => 'required|integer|min:0',
            'tagihan_bulan_lalu' => 'nullable|numeric'
        ]);

        $pemakaian = $request->stand_kini - $meter->stand_lama;

        if ($pemakaian < 0) {
            return back()->withErrors([
                'stand_kini' => 'Stand kini tidak boleh lebih kecil dari stand lama'
            ]);
        }

        // $totalBayar =
        //     self::ABONEMEN +
        //     ($pemakaian * self::TARIF_PER_M3) -
        //     ($request->tagihan_bulan_lalu ?? 0);
        if ($meter->status_lunas) {
            abort(403, 'Tagihan sudah lunas — tidak boleh diubah');
        }
        $hutang = $request->tagihan_bulan_lalu ?? 0;
        if ($hutang > 0) {
            $hutang = -1 * $hutang;
        }
        $meter->update([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'stand_kini' => $request->stand_kini,
            'pemakaian' => $pemakaian,
            'tagihan_bulan_lalu' => $hutang,
            // 'total_bayar' => $totalBayar
        ]);
        return redirect()->route('air.index')
            ->with('success','Data meter berhasil diupdate');
    }


    public function destroy(MeterAir $meter)
    {
        if ($meter->status_lunas) {
            abort(403, 'Data lunas tidak boleh dihapus');
        }

        $meter->delete();
        return back()->with('success','Data meter berhasil dihapus');
    }
    public function getPrevStand(Request $request)
    {
        $prev = MeterAir::prevRecord(
            $request->user_id,
            $request->bulan,
            $request->tahun
        );

        if (!$prev) {
            return response()->json([
                'stand_lama' => 0,
                'tagihan' => 0,
                'status_lunas' => 1
            ]);
        }

        $total = self::ABONEMEN +
                ($prev->pemakaian * self::TARIF_PER_M3) -
                ($prev->tagihan_bulan_lalu ?? 0);

        return response()->json([
            'stand_lama' => $prev->stand_kini,
            'tagihan' => $prev->status_lunas ? 0 : $total,
            'status_lunas' => $prev->status_lunas ? 1 : 0
        ]);
    }

    public function setLunas(Request $request, $id)
    {
        $data = MeterAir::findOrFail($id);
        $data->status_lunas = true;
        $data->save();

        return redirect()->route('air.index', [
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ])->with('success', 'Tagihan ditandai LUNAS');
    }
}
