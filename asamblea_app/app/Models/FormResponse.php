<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    protected $table = 'form_responses';
    protected $fillable = ['form_id', 'user_id', 'response_data'];
    protected $casts = [
        'response_data' => 'array'
    ];


    public function form()
    {
        return $this->belongsTo(Form::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
