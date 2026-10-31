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



    public function scopeForUser($query, $user){
   if(!$user->hasRole("admin")){
    return $query->where('user_id', $user->id);
   }
   return $query;
}  


public function scopeWithStatus($query, $status){

return $query->where('status', $status);


}

public function scopeWithPriority($query, $priority){

   return $query->where('priority', $priority);
}









}
