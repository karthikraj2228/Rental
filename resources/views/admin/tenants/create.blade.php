@extends('layouts.admin')

@section('header', 'Onboard New Tenant')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        
        <form action="{{ route('admin.tenants.store') }}" method="POST" class="space-y-8">
            @csrf
            
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
            
            <!-- Personal Info -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <!-- <input 
                         type="text" 
                         name="name" 
                         value="{{ old('name') }}" 
                         required 
                         class="form-input w-full px-3 py-3 rounded-xl 
                                border-gray-300 
                                focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"> -->
                         <input
    type="text"
    name="name"
    value="{{ old('name') }}"
    required
    class="form-input w-full px-3 py-3 rounded-xl
           bg-white text-gray-900
           border border-gray-300
           focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500
           @error('name') border-red-500 @enderror"
/>

                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="form-input w-full px-3 py-3 rounded-xl 
                        border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2 
                        @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email (Optional)</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID Proof No.</label>
                        <input type="text" name="id_proof" value="{{ old('id_proof') }}" class="form-input w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('id_proof') border-red-500 @enderror">
                        @error('id_proof')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Property & Payment -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2">Unit Assignment</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Room/Unit</label>
                        <select name="room_id" required class="form-select w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('room_id') border-red-500 @enderror">
                            <option value="">-- Choose a Vacant Unit --</option>
                            @foreach($vacantRooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->house->name }} - Room {{ $room->room_no }} ({{ $room->name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('room_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                      <div >
                        <label class="block text-sm font-medium text-gray-700 mb-2">Agreement Type</label>
                        <select name="type" id="type" required onchange="handleAgreementTypeChange()"
                        class="form-select w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('type') border-red-500 @enderror">
                            <option value="">-- Choose Agreement Type --</option>
                            <option value="Rent" {{ old('type') == 'Rent' ? 'selected' : '' }}>Monthly Rent</option>
                            <option value="Lease" {{ old('type') == 'Lease' ? 'selected' : '' }}>Lease</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="rent_field" class="hidden" >
                        <div id="maintenance_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rent Amount</label>
</div>  
                        <div id="lease_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lease Amount</label>
</div>  
                        <input type="number" name="rent_amount" value="{{ old('rent_amount') }}" step="0.01" required class="form-input w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('rent_amount') border-red-500 @enderror">
                        @error('rent_amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                     
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance</label>
                        <input type="number" name="maintenance_amount" value="{{ old('maintenance_amount') }}" step="0.01" class="form-input w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('maintenance_amount') border-red-500 @enderror">
                        @error('maintenance_amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Advance Payment</label>
                        <input type="number" name="total_advance" value="{{ old('total_advance') }}" step="0.01" class="form-input w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('total_advance') border-red-500 @enderror" placeholder="Total Advance Payment">
                        @error('total_advance')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Initial Advance Payment</label>
                        <input type="number" name="initial_advance" value="{{ old('initial_advance') }}" step="0.01" class="form-input w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('initial_advance') border-red-500 @enderror" placeholder="Amount collected now
                        ">
                        @error('initial_advance')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Move In Date</label>
                        <input type="date" name="move_in_date" value="{{ old('move_in_date') }}" required class="form-input w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('move_in_date') border-red-500 @enderror">
                        @error('move_in_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                  
                </div>
            </div>

            <!-- Account Access -->
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                <div class="flex items-start gap-3">
                    <div class="flex items-center h-5">
                        <input id="create_login" name="create_login" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="flex-1">
                        <label for="create_login" class="font-medium text-gray-700">Enable Mobile App Access</label>
                        <p class="text-gray-500 text-xs">Tenant can login using email and password to view bills.</p>
                        
                        <div id="password_field" class="hidden mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Set Password</label>
                            <input type="password" name="password" class="form-input w-full px-3 py-3 rounded-xl border border-gray-300 
                        focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2  @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.tenants.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium transition">Cancel</a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 font-semibold shadow-lg shadow-blue-200 transition">Complete Onboarding</button>
            </div>
        </form>
    </div>
</div>
<script>
function handleAgreementTypeChange() {
    const type = document.getElementById('type').value;
    console.log(type);
    if(type == 'Rent') {
        document.getElementById('maintenance_field').classList.remove('hidden');
        document.getElementById('lease_field').classList.add('hidden');
        document.getElementById('rent_field').classList.remove('hidden');
    } else {
        document.getElementById('lease_field').classList.remove('hidden');
        document.getElementById('maintenance_field').classList.add('hidden');
        document.getElementById('rent_field').classList.remove('hidden');
    }
    
}
</script>
<script>
    document.getElementById('create_login').addEventListener('change', function() {
        document.getElementById('password_field').classList.toggle('hidden', !this.checked);
    });
</script>
@endsection
