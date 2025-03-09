<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'unit_price',
        'total_amount',
        'payment_method',
        'status'
    ];

    /**
     * Relación con el modelo Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($sale) {
            if ($sale->product) {
                $sale->product->increment('sales_count', $sale->quantity);
            }
        });

        static::deleted(function ($sale) {
            if ($sale->product) {
                $sale->product->decrement('sales_count', $sale->quantity);
            }
        });
    }
}
