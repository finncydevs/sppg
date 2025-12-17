<?php

namespace App\Http\Controllers;

use App\Models\MenuPlan;
use App\Models\Recipe;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuPlanController extends Controller
{
    /**
     * Menampilkan Kalender Perencanaan Menu.
     */
    public function index(Request $request)
    {
        // Default ke bulan ini jika tidak ada input
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Ambil data rencana menu di bulan tersebut
        $plans = MenuPlan::with('recipe')
            ->whereMonth('planned_date', $month)
            ->whereYear('planned_date', $year)
            ->get()
            ->groupBy(function($date) {
                return $date->planned_date->format('Y-m-d');
            });

        $recipes = Recipe::all(); // Untuk dropdown pilih menu

        return view('menu_plans.index', compact('plans', 'recipes', 'month', 'year'));
    }

    /**
     * Menyimpan Rencana Menu (Draft).
     */
    public function store(Request $request)
    {
        $request->validate([
            'planned_date' => 'required|date',
            'recipe_id' => 'required|exists:recipes,id',
            'portion_target' => 'required|numeric|min:1',
            'meal_time' => 'required|string',
        ]);

        MenuPlan::create([
            'planned_date' => $request->planned_date,
            'recipe_id' => $request->recipe_id,
            'portion_target' => $request->portion_target,
            'meal_time' => $request->meal_time,
            'is_published' => false,
        ]);

        return back()->with('success', 'Menu berhasil dijadwalkan.');
    }

    /**
     * Menghapus Rencana Menu.
     */
    public function destroy(MenuPlan $menuPlan)
    {
        if ($menuPlan->is_published) {
            return back()->with('error', 'Menu yang sudah diterbitkan tidak bisa dihapus dari sini.');
        }

        $menuPlan->delete();
        return back()->with('success', 'Rencana menu dihapus.');
    }

    /**
     * Mempublikasikan Rencana Menu menjadi Work Order (WO).
     */
    public function publish(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required'
        ]);

        $unpublishedPlans = MenuPlan::whereMonth('planned_date', $request->month)
            ->whereYear('planned_date', $request->year)
            ->where('is_published', false)
            ->get();

        if ($unpublishedPlans->isEmpty()) {
            return back()->with('error', 'Tidak ada menu draft yang perlu diterbitkan di bulan ini.');
        }

        DB::transaction(function() use ($unpublishedPlans) {
            foreach ($unpublishedPlans as $plan) {
                // Generate Nomor WO
                $dateStr = $plan->planned_date->format('Ymd');
                $count = ProductionOrder::whereDate('production_date', $plan->planned_date)->count() + 1;
                $woNumber = "WO-{$dateStr}-" . str_pad($count, 3, '0', STR_PAD_LEFT);

                // Buat WO
                ProductionOrder::create([
                    'wo_number' => $woNumber,
                    'recipe_id' => $plan->recipe_id,
                    'target_quantity' => $plan->portion_target,
                    'production_date' => $plan->planned_date,
                    'status' => 'Planned', // Status awal WO
                ]);

                // Tandai plan sebagai published
                $plan->update(['is_published' => true]);
            }
        });

        return back()->with('success', count($unpublishedPlans) . ' Rencana menu berhasil diterbitkan menjadi Perintah Kerja (WO).');
    }
}
