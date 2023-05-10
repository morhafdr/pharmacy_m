<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'total_invoices_price'
    ];

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class,'invoice_products')->withPivot('quantity');
    }

}

