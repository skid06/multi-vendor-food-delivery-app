<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'logo',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foodItems(): HasMany
    {
        return $this->hasMany(FoodItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
