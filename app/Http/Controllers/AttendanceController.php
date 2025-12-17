<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request) {
        $date = $request->input('date', date('Y-m-d'));

        // Ambil semua karyawan aktif
        $employees = Employee::where('status', 'Aktif')->get();

        // Ambil data absensi yang sudah ada di tanggal tersebut
        $attendances = Attendance::whereDate('date', $date)
                        ->get()
                        ->keyBy('employee_id');

        return view('attendances.index', compact('employees', 'date', 'attendances'));
    }

    public function store(Request $request) {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array', // Array: [employee_id => status]
        ]);

        DB::transaction(function() use ($request) {
            foreach ($request->attendance as $employeeId => $status) {
                // Update or Create
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date' => $request->date
                    ],
                    [
                        'status' => $status
                    ]
                );
            }
        });

        return redirect()->route('attendances.index', ['date' => $request->date])
                         ->with('success', 'Data absensi berhasil disimpan.');
    }
}
