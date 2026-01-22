<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Eloquent timestamps expects created_at + updated_at.
    // Since we only have created_at, turn off timestamps.
    public $timestamps = false;

    protected $fillable = ['user_id', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
