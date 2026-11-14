<?php

namespace App\Enums;

use DomainException;

enum UserRole :string
{
   case ADMIN = "admin" ;
   case USER = "user";
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
            self::SUPPORT_AGENT,
        ];
    }


    public function ensureCanBeAssignedTickets(): void
    {
        if (! $this->canManageTickets()) {
            throw new DomainException('The selected assignee must have a management role.');
        }
    }

}
