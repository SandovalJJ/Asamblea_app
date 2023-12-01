<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model {
    public function form() {
        return $this->belongsTo(Form::class);
        
    }
    protected $casts = [
        'options' => 'array', // Asegúrate de que 'options' se maneje como un array
    ];
}