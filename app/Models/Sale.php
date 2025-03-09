<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'unit_price',
        'total_amount',
        'payment_method',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($sale) {
            $product = $sale->product;
            $product->increment('sales_count', $sale->quantity);
        });

        static::deleted(function ($sale) {
            $product = $sale->product;
            $product->decrement('sales_count', $sale->quantity);
        });
    }
}
