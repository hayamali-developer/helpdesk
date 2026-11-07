<?php

namespace App\Enums;

enum UserRole :string
{
   case ADMIN = "admin" ;
   case USER = "user";
   case ADMINISTRATOR = "adminstrator" ;
   
    public static function values(){
        return array_column(self::cases(),"value");
    }
}
