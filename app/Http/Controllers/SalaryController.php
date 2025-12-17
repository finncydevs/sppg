<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

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

        DB::transaction(function() use ($data) {
            // 1. Simpan Data Gaji
            $salary = Salary::create($data);

            // 2. OTOMATISASI: Catat di Keuangan (Pengeluaran)
            Transaction::create([
                'date' => now(), // Tanggal hari ini
                'type' => 'Expense', // Tipe Pengeluaran
                'category' => 'Gaji & Upah',
                'description' => "Pembayaran Gaji Periode {$salary->period} - {$salary->employee->name}",
                'amount' => $salary->net_salary, // Sesuai gaji bersih
                'reference_type' => 'Gaji', // Referensi ke Model Salary
                'reference_id' => $salary->id
            ]);
        });

        return redirect()->route('salaries.index')->with('success', 'Gaji berhasil dicatat & Transaksi keuangan otomatis dibuat.');
    }
}
