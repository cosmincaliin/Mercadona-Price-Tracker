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

            $table->id(); // Esto crea automáticamente una columna 'id' que es autoincremental y la clave primaria.
            $table->string('_id'); // Asegúrate de que esta línea esté presente para crear la columna '_id' que necesitas.
            $table->string('slug')->nullable();
            $table->boolean('published')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('display_name')->nullable();
            $table->json('historic_price')->nullable(); // Almacenará el historial de precios como JSON
            $table->timestamps();

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
