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
        $categories = DB::table('categories')->get();
        DB::table('products')->update(['category_id' => null]);
        foreach ($categories as $category) {
            DB::table('products')
                ->whereAny(['name', 'description'], 'LIKE', "%{$category->name}%")
                ->update(['category_id' => $category->id]);
        }
        if (\count($categories) > 0) {
            DB::table('products')
                ->whereNull('category_id')
                ->update(['category_id' => $categories[0]->id]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->unsignedBigInteger('category_id')->nullable()->change();
        });
    }
};
