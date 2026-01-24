@extends('layouts.admin')

@section('header', 'Tenant Settlement')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <div class="mb-6 text-center">
            <h2 class="text-xl font-bold text-gray-800">Final Settlement</h2>
            <p class="text-gray-500 text-sm">Vacating process for {{ $tenant->name }}</p>
        </div>

        <div class="bg-blue-50 rounded-xl p-4 mb-6 text-sm text-blue-800">
            <div class="flex justify-between mb-1">
                <span>Total Advance Paid:</span>
                <span class="font-bold">${{ number_format($tenant->total_advance, 2) }}</span>
            </div>
        </div>

        <form action="{{ route('admin.payments.process-settlement', $tenant) }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deduction / Charges</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-400">$</span>
                    <input type="number" name="deduction_charge" required step="0.01" min="0" value="0"
                        class="form-input w-full pl-8 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        oninput="calculateRefund(this.value)">
                </div>
                <p class="text-xs text-gray-500 mt-1">Maintenance, damages, or unpaid dues.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                <textarea name="remarks" rows="3" class="form-input w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Reason for deduction..."></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vacating Date</label>
                <input type="date" name="settlement_date" required value="{{ date('Y-m-d') }}" class="form-input w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="border-t border-gray-100 pt-4">
                <div class="flex justify-between items-center mb-6">
                    <span class="font-bold text-gray-700">Refundable Amount:</span>
                    <span class="text-2xl font-bold text-green-600" id="refundSpan">${{ number_format($tenant->total_advance, 2) }}</span>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium transition">Cancel</a>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-red-600 text-white hover:bg-red-700 font-semibold shadow-lg shadow-red-200 transition" onclick="return confirm('Please confirm this settlement. This action cannot be undone.')">
                        Confirm & Vacate
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const totalAdvance = {{ $tenant->total_advance }};
    function calculateRefund(deduction) {
        const refund = totalAdvance - deduction;
        document.getElementById('refundSpan').innerText = '$' + refund.toFixed(2);
    }
</script>
@endsection
