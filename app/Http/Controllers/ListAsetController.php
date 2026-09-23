<?php

namespace App\Http\Controllers;

use App\Models\ListAset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListAsetController extends Controller
{
    public function index()
    {
        $listAsets = ListAset::all();
        return view('Aset.index', compact('listAsets'));
    }

    public function list()
    {
        $listAset = ListAset::all();
    }

    public function create()
    {
        $nextKode = $this->generateKodeAset();
        return view('Aset.create', compact('nextKode'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori'  => 'required|string|max:255',
            'lokasi'    => 'required|string|max:255',
            'nama'      => 'required|string|max:255',
            // khusus kategori "Lainnya" (kategori manual)
            'kategori_lainnya' => 'nullable|string|max:255',
        ]);

        if ($request->kategori === 'Lainnya') {
            $request->validate([
                'kategori_lainnya' => ['required', 'string', 'max:255'],
            ]);

            // saat "Lainnya", nilai field kategori diambil dari input manual
            $validated['kategori'] = $request->kategori_lainnya;
        }

        $validated['kode_aset'] = $this->generateKodeAset();
        unset($validated['kategori_lainnya']);

        ListAset::create($validated);

        return redirect()->route('aset.index')
            ->with('success', 'Asset berhasil ditambahkan');
    }

    public function edit($id)
    {
        $listAset = ListAset::findOrFail($id);
        return view('Aset.edit', compact('listAset'));
    }

    public function update(Request $request, $id)
    {
        $listAset = ListAset::findOrFail($id);

        $validated = $request->validate([
            'kategori'  => 'required|string|max:255',
            'nama'      => 'required|string|max:255',
            'lokasi'    => 'required|string|max:255',
            'kategori_lainnya' => 'nullable|string|max:255',
        ]);

        if ($request->kategori === 'Lainnya') {
            $request->validate([
                'kategori_lainnya' => ['required', 'string', 'max:255'],
            ]);

            $validated['kategori'] = $request->kategori_lainnya;
        }

        unset($validated['kategori_lainnya']);

        $listAset->update($validated);

        return redirect()->route('aset.index')
            ->with('success', 'Asset updated successfully');
    }

    public function delete($id)
    {
        $listAset = ListAset::findOrFail($id);
        $listAset->delete();

        return redirect()->route('aset.index')
            ->with('success', 'Asset deleted successfully');
    }

    public function detail($id)
    {
        $listAset = ListAset::findOrFail($id);

        return view('Aset.detail', compact('listAset'));
    }

    /**
     * Generate kode aset otomatis dengan format AL-0001, AL-0002, dst.
     */
    protected function generateKodeAset(): string
    {
        $prefix = 'AL-';

        $last = ListAset::where('kode_aset', 'like', $prefix.'%')
            ->orderBy('kode_aset', 'desc')
            ->first();

        if (! $last) {
            return $prefix.'0001';
        }

        $lastNumber = (int) substr($last->kode_aset, strlen($prefix));
        $nextNumber = $lastNumber + 1;

        return $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
