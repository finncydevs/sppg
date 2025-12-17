<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index() {
        $schools = School::latest()->paginate(10);
        return view('schools.index', compact('schools'));
    }

    public function create() { return view('schools.create'); }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        School::create($request->all());
        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function edit(School $school) { return view('schools.edit', compact('school')); }

    public function update(Request $request, School $school) {
        $request->validate(['name' => 'required']);
        $school->update($request->all());
        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil diperbarui.');
    }

    public function destroy(School $school) {
        $school->delete();
        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil dihapus.');
    }
}
