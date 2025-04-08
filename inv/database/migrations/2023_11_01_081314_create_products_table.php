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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained(); // shorthand for foreign key constraint
            $table->string('name');
            $table->unsignedInteger('stock')->default(0);     // ✅ better as integer
            $table->unsignedInteger('stock_min')->nullable(); // ✅ add
            $table->unsignedInteger('stock_max')->nullable(); // ✅ add
            $table->integer('price');
            $table->string('image')->nullable();
            // $table->string('qr_code')->nullable();            // ✅ add
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
