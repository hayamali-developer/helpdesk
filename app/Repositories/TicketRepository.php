<?php

use App\Models\User;



class TicketRepository {


public function create (array $data , User $user) {

   return $user->tickets()->create($data);

}



}