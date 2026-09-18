<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
            $table->string('code', 64)->unique();
            $table->string('name', 255);
            $table->text('description');
        });

        $currentTstmp = now();

        DB::table('categories')->insert([
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'CT001',
                'name' => 'PHP',
                'description' => "PHP\r\nDescription",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'CT002',
                'name' => 'Python',
                'description' => "Python\r\nDescription",
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'CT003',
                'name' => 'JavaScript',
                'description' => "JavaScript\r\nDescription",
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
