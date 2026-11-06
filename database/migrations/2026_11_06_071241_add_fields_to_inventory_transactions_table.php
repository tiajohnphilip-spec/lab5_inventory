<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('type');
            $table->integer('quantity');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();

            $table->index(['product_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'type']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);

            $table->dropColumn([
                'product_id',
                'user_id',
                'type',
                'quantity',
                'reference_number',
                'notes',
            ]);
        });
    }
};