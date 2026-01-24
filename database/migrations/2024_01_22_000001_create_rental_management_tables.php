<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Houses
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Rooms
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained('houses')->onDelete('cascade');
            $table->string('room_no');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        // Tenants
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('id_proof')->nullable(); // e.g., National ID
            
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            
            $table->decimal('rent_amount', 10, 2)->default(0);
            $table->decimal('maintenance_amount', 10, 2)->default(0);
            
            $table->date('move_in_date');
            $table->date('move_out_date')->nullable();
            
            $table->decimal('total_advance', 10, 2)->default(0);
            
            $table->string('status')->default('active'); // active, vacating, left
            $table->string('type')->default('Rent'); // Rent, Lease
            
            $table->timestamps();
        });

        // Advance Payments
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->timestamps();
        });

        // Rents
        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            
            $table->integer('from_unit')->default(0); // EB start
            $table->integer('to_unit')->default(0); // EB end
            
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('eb_amount', 10, 2)->default(0);
            $table->decimal('maintenance_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            $table->string('status')->default('pending'); // pending, paid, partial
            $table->string('month'); // e.g., "2024-01"
            
            $table->timestamps();
        });

        // Tenant Settlements (Vacating)
        Schema::create('tenant_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            
            $table->decimal('advance_amount', 10, 2);
            $table->decimal('deduction_charge', 10, 2)->default(0);
            $table->decimal('refundable_amount', 10, 2);
            
            $table->text('remarks')->nullable();
            $table->date('settlement_date');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_settlements');
        Schema::dropIfExists('rents');
        Schema::dropIfExists('advance_payments');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('houses');
    }
};
