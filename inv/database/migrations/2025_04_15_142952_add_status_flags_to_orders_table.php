<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_approved')->nullable()->after('kaunit_id');
            $table->boolean('is_rejected')->nullable()->after('is_approved');
            $table->boolean('is_acknowledged')->nullable()->after('is_rejected');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'is_rejected', 'is_acknowledged']);
        });
    }
};

