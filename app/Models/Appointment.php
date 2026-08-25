<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'interest', 'preferred_date', 'preferred_time', 'message', 'status', 'confirmation_code'];

    protected function casts(): array
    {
        return ['preferred_date' => 'date'];
    }
}
