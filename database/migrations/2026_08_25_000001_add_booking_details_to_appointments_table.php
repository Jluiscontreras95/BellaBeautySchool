<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->time('preferred_time')->after('preferred_date');
            $table->text('message')->nullable()->after('preferred_time');
            $table->string('status', 30)->default('pending')->index()->after('message');
            $table->string('confirmation_code', 8)->unique()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique(['confirmation_code']);
            $table->dropColumn(['preferred_time', 'message', 'status', 'confirmation_code']);
        });
    }
};
