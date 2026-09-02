<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('price');
            $table->enum('status', ['active', 'inactive'])
                ->default('active')
                ->after('is_featured');

            $table->index('is_featured');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['status']);

            $table->dropColumn([
                'is_featured',
                'status',
            ]);
        });
    }
};
