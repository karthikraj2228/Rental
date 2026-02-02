@extends('layouts.admin')

@section('header', 'Generate Rent Invoice')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Tenant</label>
                <select name="tenant_id" required 
                class="form-input w-full px-3 py-3 rounded-xl
           bg-white text-gray-900
           border border-gray-300
           focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Choose Active Tenant --</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}">{{ $tenant->name }} (Room {{ $tenant->room->room_no }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Billing Month</label>
                <input type="month" name="month" required value="{{ date('Y-m') }}" class="form-input w-full px-3 py-3 rounded-xl
           bg-white text-gray-900
           border border-gray-300
           focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 space-y-4">
                <h4 class="font-bold text-gray-800 border-b border-gray-200 pb-2">Electricity Bill</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Previous Unit</label>
                        <input type="number" name="from_unit" required min="0" class="form-input w-full rounded-lg border-gray-300 px-3 py-3">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Current Unit</label>
                        <input type="number" name="to_unit" required min="0" class="form-input w-full rounded-lg border-gray-300 px-3 py-3">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Cost Per Unit</label>
                        <input type="number" name="eb_rate" required value="10" step="0.01" class="form-input w-full rounded-lg border-gray-300 px-3 py-3">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('admin.payments.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium transition">Cancel</a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 font-semibold shadow-lg shadow-blue-200 transition">Generate Invoice</button>
            </div>
        </form>
    </div>
</div>
@endsection
