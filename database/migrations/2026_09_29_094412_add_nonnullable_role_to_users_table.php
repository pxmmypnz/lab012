<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->whereNull('role')->update(['role' => 'USER']);

        $currentTstmp = now();

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@my-db.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@my-db.com',
                'email_verified_at' => $currentTstmp,
                'password' => Hash::make('1234'),
                'role' => 'ADMIN',
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
            ],
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'user@my-db.com'],
            [
                'name' => 'User',
                'email' => 'user@my-db.com',
                'email_verified_at' => $currentTstmp,
                'password' => Hash::make('1234'),
                'role' => 'USER',
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
            ],
        );

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable(true)->change();
        });
    }
};
