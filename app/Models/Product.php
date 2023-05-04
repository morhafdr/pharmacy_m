<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    protected $fillable =[
        'product_name','price','quantity',
        'expiry_date','purchase_id',
    ];
    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class,);
    }
}
