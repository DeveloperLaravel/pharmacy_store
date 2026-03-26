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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('scientific_name')->nullable();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('dosage_form');
            $table->string('strength')->nullable();
            $table->string('unit');
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->date('expires_at')->nullable();
            $table->boolean('requires_prescription')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['name', 'scientific_name']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
