<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Rent;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_houses' => House::count(),
            'total_rooms' => Room::count(),
            'vacant_rooms' => Room::doesntHave('tenant')->count(),
            'occupied_rooms' => Room::has('tenant')->count(),
            'total_tenants' => Tenant::where('status', 'active')->count(),
            'pending_rent' => Rent::where('status', 'pending')->sum('total_amount'),
            'collected_rent_this_month' => Rent::where('month', date('Y-m'))
                                            ->where('status', 'paid')
                                            ->sum('total_amount'),
        ];

        $overdue_rents = Rent::with(['tenant', 'room.house'])
                             ->where('status', 'pending')
                             ->latest()
                             ->take(5)
                             ->get();

        return view('admin.dashboard', compact('stats', 'overdue_rents'));
    }
}
