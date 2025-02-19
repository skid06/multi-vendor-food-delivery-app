<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'status',
        'total_amount',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Restaurant, $this>
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
