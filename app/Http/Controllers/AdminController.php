<?php

namespace App\Http\Controllers;

use App\Models\Stunting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total'        => Stunting::count(),
            'zona_merah'   => Stunting::where('status', 'Stunting')->count(),
            'zona_kuning'  => Stunting::where('status', 'Stunting Sedang')->count(),
            'zona_hijau'   => Stunting::where('status', 'Normal')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function index()
    {
        $stuntings = Stunting::orderBy('nama')->paginate(15);
        return view('admin.stunting.index', compact('stuntings'));
    }

    public function create()
    {
        return view('admin.stunting.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'desa'      => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status'    => 'required|in:Stunting,Stunting Sedang,Normal',
            // per tahun — opsional
            'balita_2020' => 'nullable|integer', 'stunting_2020' => 'nullable|integer', 'prevalensi_2020' => 'nullable|numeric',
            'balita_2021' => 'nullable|integer', 'stunting_2021' => 'nullable|integer', 'prevalensi_2021' => 'nullable|numeric',
            'balita_2022' => 'nullable|integer', 'stunting_2022' => 'nullable|integer', 'prevalensi_2022' => 'nullable|numeric',
            'balita_2023' => 'nullable|integer', 'stunting_2023' => 'nullable|integer', 'prevalensi_2023' => 'nullable|numeric',
            'balita_2024' => 'nullable|integer', 'stunting_2024' => 'nullable|integer', 'prevalensi_2024' => 'nullable|numeric',
            'balita_2025' => 'nullable|integer', 'stunting_2025' => 'nullable|integer', 'prevalensi_2025' => 'nullable|numeric',
        ]);

        Stunting::create($request->all());

        return redirect()->route('admin.stuntings.index')->with('success', 'Data Puskesmas berhasil ditambahkan!');
    }

    public function edit(Stunting $stunting)
    {
        return view('admin.stunting.edit', compact('stunting'));
    }

    public function update(Request $request, Stunting $stunting)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'desa'      => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status'    => 'required|in:Stunting,Stunting Sedang,Normal',
            'balita_2020' => 'nullable|integer', 'stunting_2020' => 'nullable|integer', 'prevalensi_2020' => 'nullable|numeric',
            'balita_2021' => 'nullable|integer', 'stunting_2021' => 'nullable|integer', 'prevalensi_2021' => 'nullable|numeric',
            'balita_2022' => 'nullable|integer', 'stunting_2022' => 'nullable|integer', 'prevalensi_2022' => 'nullable|numeric',
            'balita_2023' => 'nullable|integer', 'stunting_2023' => 'nullable|integer', 'prevalensi_2023' => 'nullable|numeric',
            'balita_2024' => 'nullable|integer', 'stunting_2024' => 'nullable|integer', 'prevalensi_2024' => 'nullable|numeric',
            'balita_2025' => 'nullable|integer', 'stunting_2025' => 'nullable|integer', 'prevalensi_2025' => 'nullable|numeric',
        ]);

        $stunting->update($request->all());

        return redirect()->route('admin.stuntings.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(Stunting $stunting)
    {
        $stunting->delete();
        return redirect()->route('admin.stuntings.index')->with('success', 'Data berhasil dihapus!');
    }
}