<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Session::has('user')) {
            return redirect('/login');
        }

        $totalLocations = Location::count();
        $activeLocations = Location::where('status', 'active')->count();
        $categories = Location::distinct()->pluck('category');

        return view('admin.dashboard', compact('totalLocations', 'activeLocations', 'categories'));
    }

    public function index()
    {
        if (!Session::has('user')) {
            return redirect('/login');
        }

        $locations = Location::latest()->paginate(10);
        return view('admin.locations', compact('locations'));
    }

    public function create()
    {
        if (!Session::has('user')) {
            return redirect('/login');
        }

        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'category' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        Location::create($request->all());

        return redirect('/admin/locations')->with('success', 'Lokasi berhasil ditambahkan!');
    }

    public function edit(Location $location)
    {
        if (!Session::has('user')) {
            return redirect('/login');
        }

        return view('admin.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'category' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $location->update($request->all());

        return redirect('/admin/locations')->with('success', 'Lokasi berhasil diperbarui!');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect('/admin/locations')->with('success', 'Lokasi berhasil dihapus!');
    }
}
