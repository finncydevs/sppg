<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index() {
        $salaries = Salary::with('employee')->latest()->paginate(10);
        return view('salaries.index', compact('salaries'));
    }

    public function create() {
        $employees = Employee::where('status', 'Aktif')->get();
        return view('salaries.create', compact('employees'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'employee_id' => 'required',
            'period' => 'required',
            'basic_salary' => 'required|numeric',
            'allowance' => 'required|numeric',
            'deduction' => 'required|numeric',
        ]);

        $data['net_salary'] = $data['basic_salary'] + $data['allowance'] - $data['deduction'];
        Salary::create($data);

        return redirect()->route('salaries.index')->with('success', 'Gaji berhasil dicatat.');
    }
}
