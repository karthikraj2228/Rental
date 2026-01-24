<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\House;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\AdvancePayment;
use App\Models\Rent;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@rental.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '1234567890',
            ]
        );

        // Create Houses
        $house1 = House::create([
            'name' => 'Green Valley Apartments',
            'address' => '123 Main Street, Springfield',
            'owner_id' => $admin->id,
        ]);

        $house2 = House::create([
            'name' => 'Sunset Villa',
            'address' => '456 Oak Avenue, Downtown',
            'owner_id' => $admin->id,
        ]);

        // Create Rooms for House 1
        $room101 = Room::create(['house_id' => $house1->id, 'room_no' => '101', 'name' => 'Ground Floor']);
        $room102 = Room::create(['house_id' => $house1->id, 'room_no' => '102', 'name' => 'Ground Floor']);
        $room201 = Room::create(['house_id' => $house1->id, 'room_no' => '201', 'name' => 'First Floor']);

        // Create Rooms for House 2
        $room1 = Room::create(['house_id' => $house2->id, 'room_no' => 'A1', 'name' => 'Studio']);
        $room2 = Room::create(['house_id' => $house2->id, 'room_no' => 'A2', 'name' => 'Studio']);

        // Create Tenant Users
        $tenantUser1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'phone' => '9876543210',
        ]);

        $tenantUser2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'phone' => '9876543211',
        ]);

        // Create Tenants
        $tenant1 = Tenant::create([
            'user_id' => $tenantUser1->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '9876543210',
            'id_proof' => 'DL123456',
            'room_id' => $room101->id,
            'rent_amount' => 500,
            'maintenance_amount' => 50,
            'move_in_date' => now()->subMonths(3),
            'total_advance' => 1000,
            'status' => 'active',
            'type' => 'Rent',
        ]);

        $tenant2 = Tenant::create([
            'user_id' => $tenantUser2->id,
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '9876543211',
            'id_proof' => 'DL789012',
            'room_id' => $room1->id,
            'rent_amount' => 600,
            'maintenance_amount' => 75,
            'move_in_date' => now()->subMonths(2),
            'total_advance' => 1200,
            'status' => 'active',
            'type' => 'Lease',
        ]);

        // Record Advance Payments
        AdvancePayment::create([
            'tenant_id' => $tenant1->id,
            'amount' => 1000,
            'date' => now()->subMonths(3),
        ]);

        AdvancePayment::create([
            'tenant_id' => $tenant2->id,
            'amount' => 1200,
            'date' => now()->subMonths(2),
        ]);

        // Generate Sample Rents
        // Tenant 1 - Last 2 months
        Rent::create([
            'tenant_id' => $tenant1->id,
            'room_id' => $room101->id,
            'from_unit' => 100,
            'to_unit' => 150,
            'rent_amount' => 500,
            'eb_amount' => 50,
            'maintenance_amount' => 50,
            'total_amount' => 600,
            'status' => 'paid',
            'month' => now()->subMonth()->format('Y-m'),
        ]);

        Rent::create([
            'tenant_id' => $tenant1->id,
            'room_id' => $room101->id,
            'from_unit' => 150,
            'to_unit' => 200,
            'rent_amount' => 500,
            'eb_amount' => 50,
            'maintenance_amount' => 50,
            'total_amount' => 600,
            'status' => 'pending',
            'month' => now()->format('Y-m'),
        ]);

        // Tenant 2 - Current month
        Rent::create([
            'tenant_id' => $tenant2->id,
            'room_id' => $room1->id,
            'from_unit' => 200,
            'to_unit' => 250,
            'rent_amount' => 600,
            'eb_amount' => 60,
            'maintenance_amount' => 75,
            'total_amount' => 735,
            'status' => 'pending',
            'month' => now()->format('Y-m'),
        ]);

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Admin: admin@rental.com / password');
        $this->command->info('Tenant 1: john@example.com / password');
        $this->command->info('Tenant 2: jane@example.com / password');
    }
}
