<?php

namespace App\Models;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'priority',
        'status',

    ];

    protected $casts = [
        'priority' => \App\Enums\TicketPriority::class,
        'status' => \App\Enums\TicketStatus::class,
    ];


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
