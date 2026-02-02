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

 
 
<!-- Recent History -->
<div>
    <h3 class="font-bold text-gray-800 mb-3 px-1">Histroy</h3>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
       

       
            <table class="table-auto w-full border-collapse divide-y divide-gray-200 m-3 ">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Month</th>
                        <th class="pb-3">Rent</th>
                        <th class="pb-3">Eb</th>
                        <th class="pb-3">Maintance</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                     @forelse($data as $rent)
                    <tr class="text-left text-sm space-x-4 space-y-2">

                        <td class="pb-3">{{ \Carbon\Carbon::parse($rent->month)->format('M Y') }}</td>
                        <td class="pb-3">${{ number_format($rent->rent_amount, 0) }}</td>
                        <td class="pb-3">${{ number_format($rent->eb_amount, 0) }}</td>
                        <td class="pb-3">${{ number_format($rent->maintenance_amount, 0) }}</td>
                        <td class="pb-3">
                            <span class="{{ $rent->status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($rent->status) }}
                            </span>
                        </td>
                        <td class="pb-3">{{ $rent->updated_at->format('d M Y') }}</td>
                    </tr>
                    @empty
         <div class="p-6 text-center text-gray-500 text-sm">No activity yet.</div>
        @endforelse
                </tbody>

            </table>

         
       
        
    </div>
</div>
@endsection
