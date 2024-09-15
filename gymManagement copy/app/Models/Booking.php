<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'price',
        'start_time',
        'end_time'
 ];

    public function users () {
        return $this->belongsTo(User::class);
    }
}
