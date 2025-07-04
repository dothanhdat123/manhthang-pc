<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'message', 'store_id'];
    
    // app/Models/Message.php

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


}
