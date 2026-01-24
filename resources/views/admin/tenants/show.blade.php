@extends('layouts.admin')

@section('header', 'Tenant Profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Profile Card -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="h-24 w-24 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                {{ substr($tenant->name, 0, 1) }}
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ $tenant->name }}</h2>
            <p class="text-gray-500 text-sm">{{ $tenant->email ?? $tenant->phone }}</p>
            <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold uppercase tracking-wide">
                {{ $tenant->status }}
            </span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Contract Details</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Property</span>
                    <span class="font-medium text-right">{{ $tenant->room->house->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Unit</span>
                    <span class="font-medium">Room {{ $tenant->room->room_no }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Move In</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($tenant->move_in_date)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Rent</span>
                    <span class="font-medium">${{ number_format($tenant->rent_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Advance</span>
                    <span class="font-medium">${{ number_format($tenant->total_advance, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Paid Advance</span>
                    <span class="font-medium">${{ number_format($total_advance_payed, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Remaining Advance</span>
                    <span class="font-medium">${{ number_format($tenant->total_advance -  $total_advance_payed,2) }}  </span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between">
            <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Advance Payment Details</h3>
            <a href="{{ route('admin.tenants.advance-payments', $tenant->id) }}" class="font-bold text-blue-600 mb-4 border-b border-gray-100 pb-2"  >Add  </a>
            </div>
              
            @foreach ($tenant->advancePayments ?? [] as $advance_payment)
            <div class="space-y-3 text-sm py-1">
                <div class="flex justify-between gap-2">
                    <span class="text-gray-500">
                      {{ \Carbon\Carbon::parse($advance_payment->date)->format('d-m-Y') }}
                    </span>
                    <span class="font-medium text-right">
                        ₹{{ number_format($advance_payment->amount, 2) }}
                    </span>
                </div>
            </div>
            @endforeach
     </div>

        @if($tenant->status == 'active')
        <div class="bg-red-50 rounded-xl border border-red-100 p-6 text-center">
            <h3 class="font-bold text-red-800 mb-2">End Tenancy</h3>
            <p class="text-xs text-red-600 mb-4">Initiate settlement and move-out process.</p>
            <a href="{{ route('admin.payments.settlement', $tenant) }}" class="inline-block w-full bg-red-600 text-white py-2 rounded-lg font-medium hover:bg-red-700 transition">Vacate & Settle</a>
        </div>
        @elseif($tenant->settlement)
         <div class="bg-gray-50 rounded-xl border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-2">Settlement Details</h3>
             <ul class="text-sm space-y-2">
                <li class="flex justify-between"><span>Advance</span> <span>${{ $tenant->settlement->advance_amount }}</span></li>
                <li class="flex justify-between text-red-600"><span>Deductions</span> <span>-${{ $tenant->settlement->deduction_charge }}</span></li>
                <li class="flex justify-between font-bold border-t pt-2"><span>Refunded</span> <span>${{ $tenant->settlement->refundable_amount }}</span></li>
             </ul>
        </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Quick Actions -->
        @if($tenant->status == 'active')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm hover:border-blue-500 transition text-left group">
                <a href="/admin/payments/create">
                <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-2 group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="bi bi-receipt"></i>
                </div>
                <h4 class="font-bold text-gray-800">Generate Rent</h4>
                <p class="text-xs text-gray-500">Create new invoice for this month</p>
                </a>
            </button>
             <button class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm hover:border-green-500 transition text-left group">
                <div class="h-10 w-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center mb-2 group-hover:bg-green-600 group-hover:text-white transition">
                    <i class="bi bi-cash"></i>
                </div>
                <h4 class="font-bold text-gray-800">Record Payment</h4>
                <p class="text-xs text-gray-500">Add payment for rent or advance</p>
            </button>
        </div>
        @endif

        <!-- Payment History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Payment History</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($tenant->rents->sortByDesc('created_at') as $rent)
                <div class="p-6 flex items-center justify-between">
                    <div>
                         <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($rent->month)->format('F Y') }} Rent</p>
                         <p class="text-xs text-gray-500">Generated on {{ $rent->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">${{ number_format($rent->total_amount, 2) }}</p>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $rent->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($rent->status) }}
                        </span>
                    </div>
                </div>
                @empty
                 <div class="p-8 text-center text-gray-500">No rent history available.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
