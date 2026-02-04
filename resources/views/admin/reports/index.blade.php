@extends('layouts.admin')

@section('header', 'Reports')

@section('content')
<div class="space-y-6">
    <!-- Filter Card -->
    <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Month</label>
                <input type="month" name="month" value="{{ request('month') }}" class="form-input rounded-xl border-gray-300">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-gray-800 transition">Filter</button>
                <a href="{{ route('admin.reports.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl hover:bg-gray-200 transition">Reset</a>
            </div>

            <div class="ml-auto">
                <a href="{{ route('admin.reports.export', request()->all()) }}" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
                </a>
            </div>
        </form>
    </div> -->

    <!-- Filter Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
    <form action="{{ route('admin.reports.index') }}" method="GET"
          class="flex flex-col md:flex-row md:items-end gap-4">

        <!-- Month Filter -->
        <div class="w-full md:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Filter by Month
            </label>
            <input
                type="month"
                name="month"
                value="{{ request('month') }}"
                class="w-full form-input rounded-xl border-gray-300"
            >
        </div>

        <!-- Filter & Reset -->
        <div class="flex gap-2 w-full md:w-auto">
            <button
                type="submit"
                class="flex-1 md:flex-none bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-gray-800 transition">
                Filter
            </button>

            <a
                href="{{ route('admin.reports.index') }}"
                class="flex-1 md:flex-none text-center bg-gray-100 text-gray-600 px-4 py-2 rounded-xl hover:bg-gray-200 transition">
                Reset
            </a>
        </div>

        <!-- Export -->
        <div class="w-full md:ml-auto md:w-auto">
            <a
                href="{{ route('admin.reports.export', request()->all()) }}"
                class="flex justify-center items-center gap-2 w-full bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition">
                <i class="bi bi-file-earmark-spreadsheet"></i>
                Export CSV
            </a>
        </div>

    </form>
</div>


    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-green-50 p-6 rounded-xl border border-green-100">
            <h4 class="text-green-800 font-bold mb-1">Total Collected</h4>
            <p class="text-2xl font-bold text-green-600">${{ number_format($totalCollected, 2) }}</p>
        </div>
        <div class="bg-red-50 p-6 rounded-xl border border-red-100">
            <h4 class="text-red-800 font-bold mb-1">Total Pending</h4>
            <p class="text-2xl font-bold text-red-600">${{ number_format($totalPending, 2) }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <!-- Data Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">

    <!-- 👇 Mobile horizontal scroll -->
    <div class="overflow-x-auto lg:overflow-x-visible">
        <table class="min-w-[900px] w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3">Invoice Date</th>
                    <th class="px-6 py-3">Tenant</th>
                    <th class="px-6 py-3">Property</th>
                    <th class="px-6 py-3 text-right">Rent</th>
                    <th class="px-6 py-3 text-right">Total</th>
                    <th class="px-6 py-3 text-right">Month</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($rents as $rent)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">
                        {{ $rent->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $rent->tenant->name }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $rent->room->house->name }} - {{ $rent->room->room_no }}
                    </td>
                    <td class="px-6 py-4 text-right text-gray-500">
                        @if($rent->tenant->type == 'Rent')
                        ${{ number_format($rent->rent_amount, 2) }}
                        @else
                        Lease
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                        ${{ number_format($rent->total_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                       {{ \Carbon\Carbon::createFromFormat('Y-m', $rent->month)->format('M Y') }}

                    </td>
                   <td class="px-6 py-4">
    <select
        onchange="updateStatus(this.value, {{ $rent->id }})"
        class="px-2 py-1 text-xs font-semibold rounded-full border
        {{ $rent->status === 'paid'
            ? 'bg-green-100 text-green-700 border-green-300'
            : 'bg-yellow-100 text-yellow-700 border-yellow-300' }}">
        <option value="paid" {{ $rent->status === 'paid' ? 'selected' : '' }}>Paid</option>
        <option value="pending" {{ $rent->status === 'pending' ? 'selected' : '' }}>Pending</option>
    </select>
</td>

                    <td class="px-6 py-4">
                        <a href="{{ route('admin.reports.invoice', $rent) }}"
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Invoice
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-100">
        {{ $rents->withQueryString()->links() }}
    </div>
</div>

</div>
 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function updateStatus(status, id) {
    $.ajax({
        url: `/admin/rent/update-status/${id}`,
        type: "POST",
        data: {
            status: status,
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {
            console.log("SUCCESS:", res);
            alert("Status updated successfully");
            location.reload();
        },
        error: function (xhr) {
            console.log("ERROR STATUS:", xhr.status);
            console.log("ERROR RESPONSE:", xhr.responseText);
            alert("Error occurred! Check console (F12).");
        }
    });
}
</script>



@endsection
