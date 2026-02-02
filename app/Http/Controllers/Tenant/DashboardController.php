<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
      
       $user = Auth::user();
          $tenant = $user->tenant;

        if (!$tenant) {
            abort(403, 'No tenant profile assigned to this user.');
        }

        $tenant->load(['room.house', 'rents' => function($query) {
            $query->latest();
        }]);

        $pending_rents = $tenant->rents->where('status', 'pending');
        $last_payment = $tenant->rents->where('status', 'paid')->first();

        return view('tenant.dashboard', compact('tenant', 'pending_rents', 'last_payment'));
    }
    public function histroy()
    {
         $user = Auth::user();
        $tenant = $user->tenant;
          $data = Rent::where('tenant_id', $tenant->id)->get();

        if (!$tenant) {
            abort(403, 'No tenant profile assigned to this user.');
        }

        

        return view('tenant.histroy', compact('tenant', 'data'));
    }
}
