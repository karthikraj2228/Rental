<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseController extends Controller
{
    public function index()
    {
        $houses = House::withCount('rooms')->where('owner_id', Auth::id())->get();
        return view('admin.houses.index', compact('houses'));
    }

    public function create()
    {
        return view('admin.houses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $validated['owner_id'] = Auth::id();
        House::create($validated);

        return redirect()->route('admin.houses.index')->with('success', 'Property added successfully.');
    }

    public function show(House $house)
    {
         if ($house->owner_id !== Auth::id()) {
            abort(403);
        }
        $house->load('rooms.tenant');
        return view('admin.houses.show', compact('house'));
    }

    public function edit(House $house)
    {
        if ($house->owner_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.houses.edit', compact('house'));
    }

    public function update(Request $request, House $house)
    {
        if ($house->owner_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $house->update($validated);
        return redirect()->route('admin.houses.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(House $house)
    {
        if ($house->owner_id !== Auth::id()) {
            abort(403);
        }
        $house->delete();
        return redirect()->route('admin.houses.index')->with('success', 'Property deleted successfully.');
    }

    // Room Methods (Simple implementation embedded in House Container)
    public function storeRoom(Request $request, House $house)
    {
        if ($house->owner_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'room_no' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        $house->rooms()->create($validated);

        return back()->with('success', 'Room added successfully.');
    }

    public function destroyRoom(Room $room)
    {
        if ($room->house->owner_id !== Auth::id()) {
            abort(403);
        }
        $room->delete();
        return back()->with('success', 'Room deleted successfully.');
    }
}
