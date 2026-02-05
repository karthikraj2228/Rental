<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Rent;
use App\Models\TenantSettlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $rents = Rent::with('tenant.room.house')->latest()->paginate(20);
        return view('admin.payments.index', compact('rents'));
    }

    // Rent Generation Form
    public function create()
    {
         $tenants = Tenant::where('status', 'active')->get();
        return view('admin.payments.create', compact('tenants'));
    }

    public function latestUnit($id)
    {
        $latestRent = Rent::where('tenant_id', $id)->latest()->first();
        if ($latestRent) {
            return response()->json(['to_unit' => $latestRent->to_unit]);
        }
        return response()->json(['to_unit' => 0]);
    }

    // Store Rent
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'month' => 'required|date', // YYYY-MM
            'from_unit' => 'required|integer',
            'to_unit' => 'required|integer|gte:from_unit',
            'eb_rate' => 'required|numeric|min:0', // Rate per unit
        ]);

          $tenant = Tenant::findOrFail($validated['tenant_id']);
        $type = $tenant->type;
        $unitsConsumed = $validated['to_unit'] - $validated['from_unit'];
        $ebAmount = $unitsConsumed * $validated['eb_rate'];
        if($type == 'Rent')
        {
            $totalAmount = $tenant->rent_amount + $tenant->maintenance_amount + $ebAmount;
        }
        else{
            $totalAmount = $tenant->maintenance_amount + $ebAmount;
        }
        

        Rent::create([
            'tenant_id' => $tenant->id,
            'room_id' => $tenant->room_id,
            'from_unit' => $validated['from_unit'],
            'to_unit' => $validated['to_unit'],
            'eb_rate' => $validated['eb_rate'],
            'rent_amount' => $tenant->rent_amount,
            'eb_amount' => $ebAmount,
            'maintenance_amount' => $tenant->maintenance_amount,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'month' => $validated['month'],
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Rent invoice generated successfully.');
    }

    // Show Settlement Form
    public function settlement(Tenant $tenant)
    {
        if ($tenant->status !== 'active') {
            return back()->with('error', 'Tenant is not active.');
        }
        return view('admin.payments.settlement', compact('tenant'));
    }

    // Process Settlement
    public function processSettlement(Request $request, Tenant $tenant)
    {
        if ($tenant->status !== 'active') {
            return back()->with('error', 'Tenant is not active.');
        }

        $validated = $request->validate([
            'deduction_charge' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'settlement_date' => 'required|date',
        ]);

        $refundable = $tenant->total_advance - $validated['deduction_charge'];

        DB::transaction(function () use ($tenant, $validated, $refundable) {
            TenantSettlement::create([
                'tenant_id' => $tenant->id,
                'advance_amount' => $tenant->total_advance,
                'deduction_charge' => $validated['deduction_charge'],
                'refundable_amount' => $refundable,
                'remarks' => $validated['remarks'],
                'settlement_date' => $validated['settlement_date'],
            ]);

            $tenant->update([
                'status' => 'left',
                'move_out_date' => $validated['settlement_date'],
            ]);
        });

        return redirect()->route('admin.tenants.show', $tenant)->with('success', 'Settlement processed and tenant vacated.');
    }
}
