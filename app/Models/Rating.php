<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'doctor_id', 'rating', 'comment'];

    // 🔥 علاقة مع الدكتور
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // 🔥 علاقة مع اليوزر
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
