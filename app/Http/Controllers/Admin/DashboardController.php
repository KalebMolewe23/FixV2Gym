<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        // if (auth()->user()->isAdmin()) {
        //     return view('admin.dashboard');
        // }

        // if (auth()->user()->isTrainer()) {
        //     return view('trainer.dashboard');
        // }
        // Total member dari awal tahun hingga sekarang
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $total_member = DB::table('member_gym')
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->count();

        // Total member bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $total_this_month = DB::table('member_gym')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Total member bulan lalu
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $total_last_month = DB::table('member_gym')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // Hitung persentase perubahan
        $percentage_change = $total_last_month > 0
            ? (($total_this_month - $total_last_month) / $total_last_month) * 100
            : 0;

         // Total member dari awal tahun hingga sekarang dengan idpaket = 2
        $startOfYear2 = Carbon::now()->startOfYear();
        $endOfYear2 = Carbon::now()->endOfYear();
        $total_member2 = DB::table('member_gym')
            ->where('idpaket', 2)
            ->whereBetween('created_at', [$startOfYear2, $endOfYear2])
            ->count();

        // Total member bulan ini dengan idpaket = 2
        $startOfMonth2 = Carbon::now()->startOfMonth();
        $endOfMonth2 = Carbon::now()->endOfMonth();
        $total_this_month2 = DB::table('member_gym')
            ->where('idpaket', 2)
            ->whereBetween('created_at', [$startOfMonth2, $endOfMonth2])
            ->count();

        // Total member bulan lalu dengan idpaket = 2
        $startOfLastMonth2 = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth2 = Carbon::now()->subMonth()->endOfMonth();
        $total_last_month2 = DB::table('member_gym')
            ->where('idpaket', 2)
            ->whereBetween('created_at', [$startOfLastMonth2, $endOfLastMonth2])
            ->count();

        // Hitung persentase perubahan
        $percentage_change2 = $total_last_month2 > 0
            ? (($total_this_month2 - $total_last_month2) / $total_last_month2) * 100
            : 0;

        // Total member hari ini dengan idpaket = 2
        $startOfToday3 = Carbon::now()->startOfDay();
        $endOfToday3 = Carbon::now()->endOfDay();
        $total_today3 = DB::table('member_gym')
            ->where('idpaket', 2)
            ->whereBetween('created_at', [$startOfToday3, $endOfToday3])
            ->count();

        // Total member kemarin dengan idpaket = 2
        $startOfYesterday3 = Carbon::now()->subDay()->startOfDay();
        $endOfYesterday3 = Carbon::now()->subDay()->endOfDay();
        $total_yesterday3 = DB::table('member_gym')
            ->where('idpaket', 2)
            ->whereBetween('created_at', [$startOfYesterday3, $endOfYesterday3])
            ->count();

        // Hitung persentase perubahan dibandingkan hari sebelumnya
        $percentage_change_today3 = $total_yesterday3 > 0
            ? (($total_today3 - $total_yesterday3) / $total_yesterday3) * 100
            : 0;

        $total_members = DB::table('member_gym')->where('idpaket', 2)->count();

        // Total Active (end_training >= waktu sekarang)
        $total_active = DB::table('member_gym')
        ->where('idpaket', 2)
        ->where('end_training', '>=', $now)
        ->count();

        // Total Non-Active (end_training < waktu sekarang)
        $total_non_active = DB::table('member_gym')
            ->where('idpaket', 2)
            ->where('end_training', '<', $now)
            ->count();

        // Hitung persentase Active dan Non-Active
        $percentage_active = $total_members > 0 ? ($total_active / $total_members) * 100 : 0;
        $percentage_non_active = $total_members > 0 ? ($total_non_active / $total_members) * 100 : 0;

        $monthlyData = [];
        $startOfYear3 = Carbon::now()->startOfYear();
        $endOfYear3 = Carbon::now()->endOfYear();

        // Loop untuk setiap bulan dari Januari sampai Desember
        for ($month3 = 1; $month3 <= 12; $month3++) {
            $startOfMonth3 = Carbon::create(Carbon::now()->year, $month3, 1)->startOfMonth();
            $endOfMonth3 = Carbon::create(Carbon::now()->year, $month3, 1)->endOfMonth();

            $monthlyData[] = DB::table('member_gym')
                ->whereBetween('created_at', [$startOfMonth3, $endOfMonth3])
                ->count();
        }

        if (config('app.debug')) {
            logger('Monthly Data:', $monthlyData);
        }

        return view('admin.dashboard', [
            'total_member' => $total_member,
            'percentage_change' => round($percentage_change, 1),
            'total_member2' => $total_member2,
            'percentage_change2' => round($percentage_change2, 1),
            'total_today3' => $total_today3,
            'percentage_change_today3' => round($percentage_change_today3, 1),
            'total_active' => $total_active,
            'total_non_active' => $total_non_active,
            'percentage_active' => round($percentage_active, 1),
            'percentage_non_active' => round($percentage_non_active, 1),
            'monthlyData' => $monthlyData,
        ]);
    }
}
