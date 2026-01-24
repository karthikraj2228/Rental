@extends('layouts.admin')

@section('header', 'Tenants')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <div>
            <h3 class="font-bold text-gray-800">Tenant Directory</h3>
            <p class="text-sm text-gray-500">List of all active tenants</p>
        </div>
        <a href="{{ route('admin.tenants.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 transition">
            <i class="bi bi-person-plus"></i> Add Tenant
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Property / Room</th>
                    <th class="px-6 py-3">Contact</th>
                    <th class="px-6 py-3">Rent / Maint</th>
                    <th class="px-6 py-3">Move In</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tenants as $tenant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                {{ substr($tenant->name, 0, 1) }}
                            </div>
                            <div>
                                {{ $tenant->name }}
                                <div class="text-xs text-gray-400 capitalize">{{ $tenant->type }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        <span class="block text-gray-800 font-medium">{{ $tenant->room->house->name }}</span>
                        <span class="text-xs">Room {{ $tenant->room->room_no }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        <div>{{ $tenant->phone }}</div>
                        <div class="text-xs text-gray-400">{{ $tenant->email ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-900 font-medium">
                        ${{ number_format($tenant->rent_amount, 0) }} 
                        <span class="text-gray-400 text-xs font-normal">+ {{ $tenant->maintenance_amount }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($tenant->move_in_date)->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $tenant->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($tenant->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No tenants found. Add a new tenant to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
