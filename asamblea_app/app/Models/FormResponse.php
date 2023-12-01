<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    // Especifica el nombre de la tabla si no sigue la convención de nombres de Laravel
    protected $table = 'form_responses';

    // Los atributos que son asignables masivamente
    protected $fillable = ['form_id', 'response_data'];

    // Especifica que el campo 'response_data' debe ser tratado como un array
    protected $casts = [
        'response_data' => 'array'
    ];

    // Relación con el modelo Form
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    // Aquí puedes agregar métodos adicionales según tus necesidades
}
