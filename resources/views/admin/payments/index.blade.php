@extends('layouts.admin')

@section('header', 'Payments & Invoices')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <div>
            <h3 class="font-bold text-gray-800">Rent Invoices</h3>
            <p class="text-sm text-gray-500">History of generated rent bills</p>
        </div>
        <a href="{{ route('admin.payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 transition">
            <i class="bi bi-receipt"></i> Generate Rent
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Tenant</th>
                    <th class="px-6 py-3">Room</th>
                    <th class="px-6 py-3">Month</th>
                    <th class="px-6 py-3 text-right">Rent</th>
                    <th class="px-6 py-3 text-right">EB</th>
                    <th class="px-6 py-3 text-right">Total</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rents as $rent)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">{{ $rent->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $rent->tenant->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $rent->room->room_no }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ \Carbon\Carbon::parse($rent->month)->format('M Y') }}</td>
                    <td class="px-6 py-4 text-right text-gray-500">${{ number_format($rent->rent_amount, 2) }}</td>
                    <td class="px-6 py-4 text-right text-gray-500">${{ number_format($rent->eb_amount, 2) }}</td>
                    <td class="px-6 py-4 text-right font-bold text-gray-900">${{ number_format($rent->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $rent->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($rent->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        No invoices generated yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $rents->links() }}
    </div>
</div>
@endsection
