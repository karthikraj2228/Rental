@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                <i class="bi bi-house-door text-xl"></i>
            </div>
            <span class="text-xs font-semibold px-2 py-1 bg-green-100 text-green-700 rounded-full">Active</span>
        </div>
        <h3 class="text-gray-500 text-sm font-medium">Total Properties</h3>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_houses'] }}</p>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="h-10 w-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center">
                <i class="bi bi-people text-xl"></i>
            </div>
            <span class="text-xs font-semibold px-2 py-1 bg-purple-100 text-purple-700 rounded-full">Occupied</span>
        </div>
        <h3 class="text-gray-500 text-sm font-medium">Total Tenants</h3>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_tenants'] }}</p>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="h-10 w-10 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center">
                <i class="bi bi-exclamation-circle text-xl"></i>
            </div>
            <span class="text-xs font-semibold px-2 py-1 bg-orange-100 text-orange-700 rounded-full">Pending</span>
        </div>
        <h3 class="text-gray-500 text-sm font-medium">Pending Rent (Total)</h3>
        <p class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($stats['pending_rent'], 2) }}</p>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="h-10 w-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                <i class="bi bi-cash-stack text-xl"></i>
            </div>
            <span class="text-xs font-semibold px-2 py-1 bg-green-100 text-green-700 rounded-full">Collected</span>
        </div>
        <h3 class="text-gray-500 text-sm font-medium">Rent This Month</h3>
        <p class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($stats['collected_rent_this_month'], 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Occupancy Chart Placeholder -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">
            Occupancy Overview
        </div>
        <div class="p-6 flex-1 flex items-center justify-center">
             <div class="w-full flex items-center justify-between px-10 py-6">
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-1">Total Rooms</p>
                    <p class="text-2xl font-bold">{{ $stats['total_rooms'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-1">Occupied</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['occupied_rooms'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-1">Vacant</p>
                    <p class="text-2xl font-bold text-red-500">{{ $stats['vacant_rooms'] }}</p>
                </div>
             </div>
        </div>
    </div>

    <!-- Recent Overdue -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
         <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Recent Overdue Payments</h3>
            <a href="#" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Tenant</th>
                        <th class="px-6 py-3">Property</th>
                        <th class="px-6 py-3">Amount</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($overdue_rents as $rent)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $rent->tenant->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $rent->room->house->name }} - {{ $rent->room->room_no }}</td>
                        <td class="px-6 py-4 text-gray-900 font-medium">${{ number_format($rent->total_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Overdue
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No overdue payments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
