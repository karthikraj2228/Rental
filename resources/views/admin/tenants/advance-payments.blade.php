@extends('layouts.admin')

@section('header', 'Onboard New Tenant')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="bi bi-exclamation-triangle-fill text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2">Tenants Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <p>Name: {{ $tenant->name }}</p>
                    <p>Phone: {{ $tenant->phone }}</p>
                    <p>Room: {{ $tenant->room->name }}</p>
                    <p>House: {{ $tenant->room->house->name }}</p>
                    <p>Move In Date: {{ $tenant->move_in_date }}</p>
                    <p>Rent Amount: {{ $tenant->rent_amount }}</p>
                    <p>Maintenance Amount: {{ $tenant->maintenance_amount }}</p>
                    <p>Type: {{ $tenant->type }}</p>
                    <p>Total Advance: {{ $tenant->total_advance }}</p>
                </div>
            </div>
</div>
<div class="my-6"></div>


 <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 ">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2">Advance Paied</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach ($tenant->advancePayments as  $advancePayment)
                        <p class="text-red-700 font-bold " >Amount: {{ $advancePayment->amount }}</p>
                        <p>Date: {{ $advancePayment->date }}</p>
                    @endforeach
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-4">
                    <p class="font-bold text-orange-700">Total Advance: {{ $tenant->total_advance }}</p>
                    <p class="font-bold text-blue-700">Total Advance Paied: {{ $tenant->advancePayments->sum('amount') }}</p>
                    <p class="font-bold text-green-700">Remaining Advance: {{ $tenant->total_advance - $tenant->advancePayments->sum('amount') }}</p>
                </div>
            </div>
            


<div class="my-6"></div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 ">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2">Add Advance Payment</h3>
                <form action="{{ route('admin.tenants.store-advance-payment', $tenant->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-select w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  sm:text-sm" required>
                        </div>
                        <div class="col-span-2">
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="date" class="form-select w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  sm:text-sm" required>
                        </div>
                        <div class="col-span-2">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Add Advance Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
           
</div>     
@endsection
