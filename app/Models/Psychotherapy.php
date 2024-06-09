<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psychotherapy extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'therapist_id', 'pack_id', 'firstName', 'lastName', 'email', 'phone'];
}
