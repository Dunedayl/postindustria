<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency',
        'force_exchange',
        'force_exchange_amount',
        'user_id'
    ];
}
