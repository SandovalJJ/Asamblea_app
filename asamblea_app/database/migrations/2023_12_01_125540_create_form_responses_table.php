<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            // Puedes añadir aquí campos adicionales según lo necesites
            $table->json('response_data'); // Almacena los datos de respuesta en formato JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_responses');
    }
}
