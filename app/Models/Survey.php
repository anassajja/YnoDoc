<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Survey extends Model
{
    protected $fillable = ['title', 'description', 'questions'];

    protected $casts = [
        'questions' => 'array', // Ensure questions are cast to an array
    ];
    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
