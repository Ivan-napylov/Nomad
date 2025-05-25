<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    // Указываем, какие поля можно массово заполнять
    protected $fillable = [
        'title',
        'price',
        'stock',
    ];

    /**
     * Связь "многие-ко-многим" с заказами (Order)
     * с указанием поля 'quantity' в pivot таблице
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
