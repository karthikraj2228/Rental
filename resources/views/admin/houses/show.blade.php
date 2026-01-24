@extends('layouts.admin')

@section('header')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.houses.index') }}" class="text-gray-400 hover:text-gray-600"><i class="bi bi-arrow-left"></i></a>
    <span>{{ $house->name }}</span>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Sidebar / Details -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">Property Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wide">Name</p>
                    <p class="text-gray-800 font-medium">{{ $house->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wide">Address</p>
                    <p class="text-gray-800 font-medium">{{ $house->address ?? 'N/A' }}</p>
                </div>
                <div class="pt-4">
                    <a href="{{ route('admin.houses.edit', $house) }}" class="text-blue-600 text-sm font-semibold hover:underline">Edit Details</a>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl border border-blue-100 p-6">
            <h3 class="font-bold text-blue-900 mb-2">Quick Stats</h3>
             <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex justify-between">
                    <span>Total Rooms</span>
                    <span class="font-bold">{{ $house->rooms->count() }}</span>
                </li>
                <li class="flex justify-between">
                    <span>Occupied</span>
                    <span class="font-bold">{{ $house->rooms->filter(fn($r) => $r->tenant)->count() }}</span>
                </li>
                 <li class="flex justify-between">
                    <span>Vacant</span>
                    <span class="font-bold">{{ $house->rooms->filter(fn($r) => !$r->tenant)->count() }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main / Rooms List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Rooms / Units</h3>
                
                <!-- Add Room Form (Inline/Modal Trigger) -->
                <button onclick="document.getElementById('addRoomForm').classList.toggle('hidden')" class="text-sm bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    <i class="bi bi-plus"></i> Add Room
                </button>
            </div>

            <!-- Inline Add Room Form -->
            <div id="addRoomForm" class="hidden bg-gray-50 p-6 border-b border-gray-100 animate-fade-in-down">
                <form action="{{ route('admin.houses.rooms.store', $house) }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Room No</label>
                        <input type="text" name="room_no" required placeholder="e.g. 101"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Name/Desc (Optional)</label>
                        <input type="text" name="name" placeholder="e.g. Ground Floor"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">Save</button>
                </form>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($house->rooms as $room)
                <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 {{ $room->tenant ? 'bg-purple-100 text-purple-600' : 'bg-green-100 text-green-600' }} rounded-lg flex items-center justify-center font-bold">
                            {{ $room->room_no }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $room->name ?? 'Room '.$room->room_no }}</p>
                            @if($room->tenant)
                                <p class="text-xs text-purple-600 font-medium">Occupied by {{ $room->tenant->name }}</p>
                            @else
                                <p class="text-xs text-green-600 font-medium">Vacant</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                         @if(!$room->tenant)
                            <form action="{{ route('admin.houses.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Delete this room?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    No rooms added yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
