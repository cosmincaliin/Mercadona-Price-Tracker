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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('display_name');
            $table->string('api_id_product');
            $table->string('slug')->nullable();
            $table->string('share_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->string('brand')->nullable();
            $table->string('origin')->nullable();
            $table->decimal('iva', 8, 2)->nullable(); // Decimal con 8 dígitos en total y 2 decimales
            $table->decimal('unit_price', 10, 2)->nullable(); // Decimal con 10 dígitos en total y 2 decimales
            $table->timestamps();

            // Definir la clave foránea para la columna categoria_id
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
