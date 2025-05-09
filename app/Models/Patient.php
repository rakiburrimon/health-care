<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['phone_number', 'address', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'appointments');
    }
}
