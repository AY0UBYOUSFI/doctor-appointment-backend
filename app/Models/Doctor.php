<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment; 
use Illuminate\Notifications\Notifiable;

class Doctor extends Model
{
    use HasFactory;
    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'bio',
        'user_id',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function ratings()
{
    return $this->hasMany(Rating::class);
}
public function availabilities()
{
    return $this->hasMany(Availability::class);
}
public function user()
{
    return $this->belongsTo(User::class);
    
}



}
