<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    /**
     * @var string[]
     */
    protected $fillable = [
        'questions',
        'answers',
        'type',
        'active',
    ];
}
