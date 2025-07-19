<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaccions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emisor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receptor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('monto', 15, 2)->check('monto > 0');
            $table->timestamps();

            $table->unique(['emisor_id', 'receptor_id', 'monto', 'created_at']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccions');
    }
};
