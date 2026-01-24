@extends('layouts.admin')

@section('header', 'Edit Property')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.houses.update', $house) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Property Name</label>
                <input type="text" name="name" id="name" value="{{ $house->name }}" required 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" id="address" rows="3"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">{{ $house->address }}</textarea>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <a href="{{ route('admin.houses.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 font-semibold shadow-lg shadow-blue-200 transition">
                    Update Property
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
