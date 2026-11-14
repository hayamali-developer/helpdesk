<?php

namespace App\Enums;

enum UserRole :string
{
   case ADMIN = "admin" ;
   case USER = "user";
   case ADMINISTRATOR = "adminstrator" ;
   case SUPPORT_AGENT="support_agent";
   
    public static function values(){
        return array_column(self::cases(),"value");
    }


    public function canManageTickets(): bool
    {
        return in_array($this,self::managementRoles() , true);
    }



       public static function managementRoles(): array
    {
        return [
            self::ADMIN,
            self::USER,
            self::SUPPORT_AGENT,
        ];
    }

}
