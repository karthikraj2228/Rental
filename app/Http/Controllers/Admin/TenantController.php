<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\House;
use App\Models\Room;
use App\Models\User;
use App\Models\AdvancePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
            $tenants = Tenant::with(['room.house'])->latest()->get();
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        // Get vacant rooms
          $vacantRooms = Room::doesntHave('tenant')->with('house')->get();
        return view('admin.tenants.create', compact('vacantRooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'id_proof' => 'nullable|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'move_in_date' => 'required|date',
            'rent_amount' => 'required|numeric|min:0',
            'maintenance_amount' => 'nullable|numeric|min:0',
            'type' => 'required|in:Rent,Lease',
            'initial_advance' => 'nullable|numeric|min:0',
            'total_advance' => 'nullable|numeric|min:0',
            
            // Login access
            'create_login' => 'nullable|accepted',
            'password' => 'nullable|required_if:create_login,on|min:6',
        ]);

        DB::transaction(function () use ($validated) {
            $userId = null;
            
            // Create Login User if requested
            if (isset($validated['create_login']) && $validated['email']) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'tenant',
                    'phone' => $validated['phone'],
                ]);
                $userId = $user->id;
            }

            // Create Tenant
            $tenant = Tenant::create([
                'user_id' => $userId,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'id_proof' => $validated['id_proof'],
                'room_id' => $validated['room_id'],
                'rent_amount' => $validated['rent_amount'],
                'maintenance_amount' => $validated['maintenance_amount'] ?? 0,
                'move_in_date' => $validated['move_in_date'],
                'type' => $validated['type'],
                'status' => 'active',
                'total_advance' => $validated['total_advance'] ?? 0,
            ]);

            // Record Initial Advance
            if (!empty($validated['initial_advance']) && $validated['initial_advance'] > 0) {
                AdvancePayment::create([
                    'tenant_id' => $tenant->id,
                    'amount' => $validated['initial_advance'],
                    'date' => now(),
                ]);
            }
        });

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant onboarded successfully.');
    }

    public function show(Tenant $tenant)
    {
        $id = $tenant->id; 
        $tenant->load(['room.house', 'advancePayments', 'rents', 'settlement']);
        $total_advance_payed = AdvancePayment::where('tenant_id', $id)->sum('amount');
        return view('admin.tenants.show', compact('tenant', 'total_advance_payed'));
    }

    public function advancePayments(Tenant $tenant)
    {
         $tenant->load(['advancePayments', 'room.house']);
        return view('admin.tenants.advance-payments', compact('tenant'));
    }

    public function storeAdvancePayment(Request $request, Tenant $tenant)
    {
         
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        AdvancePayment::create([
            'tenant_id' => $tenant->id,
            'amount' => $validated['amount'],
            'date' => $validated['date'],
        ]);

        return redirect()->route('admin.tenants.advance-payments', $tenant)->with('success', 'Advance payment recorded successfully.');
    }
    // Additional methods like edit, update, destroy can be added here
}
