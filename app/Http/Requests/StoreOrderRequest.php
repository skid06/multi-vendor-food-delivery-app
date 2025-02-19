<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Return true if the user is authorized to make this request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<string, string>> An associative array where keys are field names
     *                                                     and values are validation rules as strings or arrays.
     */
    public function rules(): array
    {
        return [
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array',
            'items.*.food_item_id' => 'required|exists:food_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_token' => 'required|string',
        ];
    }
}
