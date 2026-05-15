<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'preferred_otp_channel')) {
                $table->enum('preferred_otp_channel', ['email', 'sms'])->default('email')->after('otp_expires_at');
            }

            if (! Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('preferred_otp_channel')->index();
            }

            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('two_factor_enabled');
            }

            if (! Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }
        });

        if (! Schema::hasTable('otp_challenges')) {
            Schema::create('otp_challenges', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->enum('type', ['login', 'two_factor', 'password_reset']);
                $table->enum('channel', ['email', 'sms'])->default('email');
                $table->string('code');
                $table->timestamp('expires_at')->index();
                $table->timestamp('verified_at')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'type']);
            });
        }

        if (! Schema::hasTable('login_alerts')) {
            Schema::create('login_alerts', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->string('location')->nullable();
                $table->timestamp('logged_in_at')->index();
                $table->timestamps();
            });
        }

        Schema::table('products', function (Blueprint $table): void {
            if (! Schema::hasColumn('products', 'badge_text')) {
                $table->string('badge_text')->nullable()->after('rating');
            }

            if (! Schema::hasColumn('products', 'delivery_eta')) {
                $table->string('delivery_eta')->nullable()->after('badge_text');
            }

            if (! Schema::hasColumn('products', 'search_keywords')) {
                $table->text('search_keywords')->nullable()->after('meta_description');
            }
        });

        Schema::table('coupons', function (Blueprint $table): void {
            if (! Schema::hasColumn('coupons', 'description')) {
                $table->string('description')->nullable()->after('code');
            }

            if (! Schema::hasColumn('coupons', 'usage_limit')) {
                $table->unsignedInteger('usage_limit')->nullable()->after('minimum_amount');
            }
        });

        Schema::table('orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('payment_method');
            }

            if (! Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('tracking_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $columns = array_filter(['tracking_number', 'notes'], fn (string $column) => Schema::hasColumn('orders', $column));
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('coupons', function (Blueprint $table): void {
            $columns = array_filter(['description', 'usage_limit'], fn (string $column) => Schema::hasColumn('coupons', $column));
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('products', function (Blueprint $table): void {
            $columns = array_filter(['badge_text', 'delivery_eta', 'search_keywords'], fn (string $column) => Schema::hasColumn('products', $column));
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::dropIfExists('login_alerts');
        Schema::dropIfExists('otp_challenges');

        Schema::table('users', function (Blueprint $table): void {
            $columns = array_filter(['preferred_otp_channel', 'two_factor_enabled', 'last_login_at', 'last_login_ip'], fn (string $column) => Schema::hasColumn('users', $column));
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
