<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $table = 'pages';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'content',
        'active',
        'seo_title',
        'seo_description',
        'seo_keyword',
    ];
}
