@extends('layouts.tenant')

@section('content')
<!-- Welcome Section -->
<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">Hello, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
    <p class="text-gray-500 text-sm">Here is your rental summary.</p>
</div>

<!-- Current Unit Card -->
<div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Current Residence</p>
            <h3 class="text-lg font-bold text-gray-800 mt-1">{{ $tenant->room->house->name }}</h3>
            <p class="text-sm text-gray-500">Room {{ $tenant->room->room_no }}</p>
        </div>
        <div class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
            <i class="bi bi-house-door-fill"></i>
        </div>
    </div>
</div>

<!-- Payment Status Cards -->
<div class="grid grid-cols-2 gap-4 mb-6">
    <!-- Due Amount -->
    <div class="bg-red-50 p-4 rounded-2xl border border-red-100">
        <p class="text-red-600 text-xs font-medium mb-1">Due Amount</p>
        <p class="text-xl font-bold text-red-700">
            ${{ number_format($pending_rents->sum('total_amount'), 2) }}
        </p>
    </div>

    <!-- Last Paid -->
    <div class="bg-green-50 p-4 rounded-2xl border border-green-100">
        <p class="text-green-600 text-xs font-medium mb-1">Last Payment</p>
        <p class="text-xl font-bold text-green-700">
            {{ $last_payment ? '$'.number_format($last_payment->total_amount, 2) : '-' }}
        </p>
    </div>
</div>

<!-- Pending Bills -->
@if($pending_rents->count() > 0)
<div class="mb-6">
    <h3 class="font-bold text-gray-800 mb-3 px-1">Pending Bills</h3>
    <div class="space-y-3">
        @foreach($pending_rents as $rent)
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($rent->month)->format('F Y') }} Rent</p>
                <p class="text-xs text-red-500 font-medium mt-1">Due Now</p>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold text-gray-900">${{ number_format($rent->total_amount, 2) }}</p>
                <button class="text-xs bg-gray-900 text-white px-3 py-1.5 rounded-lg mt-1">Pay Now</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Recent History -->
<div>
    <h3 class="font-bold text-gray-800 mb-3 px-1">Recent Activity</h3>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @forelse($tenant->rents->take(5) as $rent)
        <div class="p-4 border-b border-gray-50 last:border-0 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 {{ $rent->status == 'paid' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }} rounded-full flex items-center justify-center">
                    <i class="bi {{ $rent->status == 'paid' ? 'bi-check-lg' : 'bi-clock' }}"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($rent->month)->format('M Y') }} Rent</p>
                    <p class="text-xs text-gray-500">{{ $rent->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <span class="text-sm font-bold {{ $rent->status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                ${{ number_format($rent->total_amount, 0) }}
            </span>
        </div>
        @empty
         <div class="p-6 text-center text-gray-500 text-sm">No activity yet.</div>
        @endforelse
    </div>
</div>
@endsection
