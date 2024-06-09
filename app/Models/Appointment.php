<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['professional_id', 'date', 'status'];

    public function isAvailable()
    {
        // Replace this with the appropriate code to check if the appointment is available
        return $this->status == 'available';
    }
}
