<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('otp_challenges') && Schema::hasColumn('otp_challenges', 'type')) {
            DB::statement("ALTER TABLE `otp_challenges` MODIFY `type` VARCHAR(50) NOT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('otp_challenges') && Schema::hasColumn('otp_challenges', 'type')) {
            DB::statement("ALTER TABLE `otp_challenges` MODIFY `type` ENUM('login', 'two_factor', 'password_reset', 'verify_phone', 'enable_2fa', 'verify') NOT NULL");
        }
    }
};
