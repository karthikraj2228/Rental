@extends('layouts.admin')

@section('header', 'My Properties')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-gray-500 text-sm">Manage your houses and buildings.</p>
    <a href="{{ route('admin.houses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2 transition">
        <i class="bi bi-plus-lg"></i> Add Property
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($houses as $house)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-200">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="bi bi-building"></i>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.houses.edit', $house) }}" class="text-gray-400 hover:text-blue-600 transition">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="{{ route('admin.houses.show', $house) }}" class="text-gray-400 hover:text-blue-600 transition">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
            
            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $house->name }}</h3>
            <p class="text-xs text-gray-500 mb-4 flex items-center gap-1">
                <i class="bi bi-geo-alt"></i> {{ Str::limit($house->address ?? 'No address set', 40) }}
            </p>
            
            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <div class="text-center">
                    <p class="text-xs text-gray-400">Total Rooms</p>
                    <p class="font-bold text-gray-800">{{ $house->rooms_count }}</p>
                </div>
                <a href="{{ route('admin.houses.show', $house) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Manage Rooms &rarr;
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <div class="h-16 w-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-house-slash text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900">No properties found</h3>
        <p class="text-gray-500 mb-6">Get started by adding your first house or building.</p>
        <a href="{{ route('admin.houses.create') }}" class="text-blue-600 font-semibold hover:underline">Add Property Now</a>
    </div>
    @endforelse
</div>
@endsection
