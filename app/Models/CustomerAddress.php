<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $table = 'customer_addresses';

    /**
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'name',
        'phone',
        'address',
        'is_default',
    ];
}
