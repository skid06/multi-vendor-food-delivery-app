<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_number',
        'is_available',
    ];

    /**
     * Get the user associated with the rider.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders assigned to the rider.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
