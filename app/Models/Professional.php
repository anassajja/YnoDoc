<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Appointment;

class Professional extends Authenticatable
{
    use HasFactory;

    protected $table = 'professionals';

    protected $fillable = ['titre', 'name', 'persoPhone', 'email', 'password', 'specialization', 'city', 'address', 'proPhone', 'location', 'description', 'confirmed'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
