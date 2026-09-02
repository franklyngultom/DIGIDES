<?php

namespace App\Http\Controllers;

use App\Models\DesaProfile;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Display the main working productivity dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $desa = DesaProfile::current();

        $recentActivities = Activity::with('causer')
            ->latest()
            ->take(6)
            ->get();

        $totalUsers = User::count();
        $activeUsers = User::active()->count();

        // Data produktivitas 3 hari untuk Sparklines (Sesuai referensi visual UI)
        $productivityStats = [
            [
                'day' => 'Mon',
                'date' => date('d', strtotime('-2 days')),
                'productive_percent' => 86,
                'productive_time' => '5h 12m',
                'work_time' => '5h 45m',
                'theme' => 'lime', // bg gradient lime
            ],
            [
                'day' => 'Tue',
                'date' => date('d', strtotime('-1 days')),
                'productive_percent' => 72,
                'productive_time' => '4h 10m',
                'work_time' => '6h 30m',
                'theme' => 'sage', // bg sage #4fa394
            ],
            [
                'day' => 'Wed',
                'date' => date('d'),
                'productive_percent' => 90,
                'productive_time' => '6h 25m',
                'work_time' => '7h 10m',
                'theme' => 'pine', // bg deep pine #0c3837
            ],
        ];

        if (Schedule::count() === 0) {
            (new \Database\Seeders\ScheduleSeeder())->run();
        }

        $upcomingSchedules = Schedule::orderBy('order')->orderBy('id')->get();

        return view('dashboard.index', compact(
            'user',
            'desa',
            'recentActivities',
            'totalUsers',
            'activeUsers',
            'productivityStats',
            'upcomingSchedules'
        ));
    }
}
