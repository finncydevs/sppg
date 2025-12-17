<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipmentDestination;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\PurchaseOrder;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $currentMonth = now()->format('Y-m');

        // KPI 1: Porsi Terdistribusi Hari Ini
        $totalPorsi = ShipmentDestination::whereHas('shipment', function($q) use ($today) {
            $q->whereDate('departure_time', $today);
        })->where('status', 'Terkirim')->sum('quantity');

        // KPI 2: Kehadiran
        $totalKaryawan = Employee::where('status', 'Aktif')->count();
        $hadirHariIni = Attendance::whereDate('date', $today)->where('status', 'Hadir')->count();
        $attendanceRate = $totalKaryawan > 0 ? round(($hadirHariIni / $totalKaryawan) * 100) : 0;

        // KPI 3: Pengeluaran Pengadaan Bulan Ini
        $totalPengadaan = PurchaseOrder::where('status', '!=', 'Cancelled')
            ->where('order_date', 'like', "$currentMonth%")
            ->sum('total_amount');

        // KPI 4: Total Expense (Pengadaan + Manual Expense)
        $manualExpense = Transaction::where('type', 'Expense')
            ->where('date', 'like', "$currentMonth%")
            ->sum('amount');

        $totalExpense = $totalPengadaan + $manualExpense;

        return view('dashboard', compact('totalPorsi', 'attendanceRate', 'totalPengadaan', 'totalExpense', 'totalKaryawan', 'hadirHariIni'));
    }
}
