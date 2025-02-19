<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'food_item_id',
        'price',
        'quantity',
    ];

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<FoodItem, $this>
     */
    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class);
    }
}
