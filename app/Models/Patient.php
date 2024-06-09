<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Patient extends Authenticatable
{
    protected $fillable = ['name', 'email', 'city', 'phone', 'age', 'password', 'confirmed', 'description'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }
    public function psychotherapies()
    {
        return $this->hasMany(Psychotherapy::class);
    }
    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
