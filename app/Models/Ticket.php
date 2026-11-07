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


    protected $attributes = [
        'status' => 'open',
    ] ;


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }



    public function scopeForUser($query, $user){
   if(!$user || $user->role !=="admin"){
    $userId= $user ? $user->id : null;
    return $query->where('user_id', $userId);
   }
   return $query;
}  


public function scopeWithStatus($query, $status){
    if(!$status){
       return $query;
    }

return $query->where('status', $status);


}

public function scopeWithPriority($query, $priority){

  if(!$priority){
       return $query;
    }

   return $query->where('priority', $priority);
}


}









