<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::users()->orderbyDesc('id')->limit(6)->get();

        $counters['total_tickets'] = Ticket::all()->count();
        $counters['opened_tickets'] = Ticket::opened()->count();
        $counters['closed_tickets'] = Ticket::closed()->count();

        $counters['total_users'] = User::users()->count();
        $counters['total_agents'] = User::agents()->count();
        $counters['total_admins'] = User::where('id', '!=', Auth::user()->id)->admins()->count();

        $charts['users'] = $this->generateUsersChartData();
        $charts['tickets'] = $this->generateTicketsChartData();

        return view('admin.dashboard', [
            'counters' => $counters,
            'users' => $users,
            'charts' => $charts,
        ]);
    }

    private function generateUsersChartData()
    {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();
        $dates = chartDates($startDate, $endDate);
        $records = User::users()->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');
        $data = $dates->merge($records);
        $chart['labels'] = [];
        $chart['data'] = [];
        foreach ($data as $key => $value) {
            $chart['labels'][] = Carbon::parse($key)->format('d F');
            $chart['data'][] = $value;
        }
        $chart['max'] = (max($chart['data']) > 9) ? max($chart['data']) + 2 : 10;
        return $chart;
    }

    private function generateTicketsChartData()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $dates = chartDates($startDate, $endDate);
        $records = Ticket::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');
        $data = $dates->merge($records);
        $chart['labels'] = [];
        $chart['data'] = [];
        foreach ($data as $key => $value) {
            $chart['labels'][] = Carbon::parse($key)->format('d F');
            $chart['data'][] = $value;
        }
        $chart['max'] = (max($chart['data']) > 9) ? max($chart['data']) + 2 : 10;
        return $chart;
    }
}
