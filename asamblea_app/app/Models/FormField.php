<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $fillable = ['label', 'type', 'options', 'is_active'];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean', // Nuevo campo
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
