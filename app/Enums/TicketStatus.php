<?php 

namespace App\Enums;

use DomainException;


enum TicketStatus :string 
{
  case OPEN ="open";
  case IN_PROGRESS ="in_progress";
  case RESOLVED ="resolved";
  case CLOSED= "closed";

public function allowedTransitions(): array
    {
        return match($this) {
            self::OPEN => [self::IN_PROGRESS],
            self::IN_PROGRESS => [self::RESOLVED],
            self::RESOLVED => [self::IN_PROGRESS, self::CLOSED],
            self::CLOSED => [],
        };
    }

  public function canTransactionTo(TicketStatus $action): bool
  {
    return in_array($action,$this->allowedTransitions(),true);
  }

public function ensureCanTransitionTo(self $newStatus): void
    {
        if ($this === $newStatus) {
            return;
        }

        if (! $this->canTransactionTo($newStatus)) {
            throw new DomainException(
                "Changing the ticket status from '{$this->value}' to '{$newStatus->value}' is not allowed."
            );
        }
    }

}