<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekapKecamatan;
use App\Models\RekapPuskesmas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = (int) $request->get('tahun', now()->year);

        $isKecamatan = in_array($tahun, [2021, 2022]);

        if ($isKecamatan) {
            $totalUnit = RekapKecamatan::where('tahun', $tahun)->count();
            $totalBalita = RekapKecamatan::where('tahun', $tahun)->sum('jumlah_balita_diukur');
            $totalStunting = RekapKecamatan::where('tahun', $tahun)->sum('jumlah_stunting');

            $prevalensi = $totalBalita > 0
                ? round(($totalStunting / $totalBalita) * 100, 2)
                : 0;

            $topData = RekapKecamatan::with('kecamatan')
                ->where('tahun', $tahun)
                ->orderByDesc('prevalensi_stunting')
                ->limit(5)
                ->get();

            $tanggalData = null;
        } else {
            $totalUnit = RekapPuskesmas::where('tahun', $tahun)->count();
            $totalBalita = RekapPuskesmas::where('tahun', $tahun)->sum('total_balita_ditimbang');
            $totalStunting = RekapPuskesmas::where('tahun', $tahun)->sum('stunting');

            $prevalensi = $totalBalita > 0
                ? round(($totalStunting / $totalBalita) * 100, 2)
                : 0;

            $topData = RekapPuskesmas::with('puskesmas')
                ->where('tahun', $tahun)
                ->orderByDesc('prev_stunted')
                ->limit(5)
                ->get();

            $tanggalData = RekapPuskesmas::where('tahun', $tahun)->max('tanggal_data');
        }

        $trenKecamatan = RekapKecamatan::selectRaw('tahun, SUM(jumlah_stunting) as total_stunting, SUM(jumlah_balita_diukur) as total_balita')
            ->groupBy('tahun')
            ->get()
            ->map(function ($item) {
                $item->prevalensi = $item->total_balita > 0
                    ? round(($item->total_stunting / $item->total_balita) * 100, 2)
                    : 0;
                return $item;
            });

        $trenPuskesmas = RekapPuskesmas::selectRaw('tahun, SUM(stunting) as total_stunting, SUM(total_balita_ditimbang) as total_balita')
            ->groupBy('tahun')
            ->get()
            ->map(function ($item) {
                $item->prevalensi = $item->total_balita > 0
                    ? round(($item->total_stunting / $item->total_balita) * 100, 2)
                    : 0;
                return $item;
            });

        $tren = collect()
            ->merge($trenKecamatan)
            ->merge($trenPuskesmas)
            ->sortBy('tahun')
            ->values();

        return view('admin.dashboard.index', compact(
            'tahun',
            'totalUnit',
            'totalBalita',
            'totalStunting',
            'prevalensi',
            'topData',
            'tanggalData',
            'isKecamatan',
            'tren'
        ));
    }
}