<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('label'); // Etiqueta del campo
            $table->string('type'); // Tipo de campo (texto, número, etc.)
            $table->json('options')->nullable(); // Columna para opciones de campos
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_fields');
    }
}
