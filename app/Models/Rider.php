<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_number',
        'is_available',
    ];

    /**
     * Get the user associated with the rider.
     * @return BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders assigned to the rider.
     * @return HasMany<Order, $this>
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
