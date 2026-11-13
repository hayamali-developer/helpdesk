<?php

namespace App\Http\Requests;

use App\Enums\TicketStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("update", TicketStatus::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           "assignee_id"=>["somtimes","nullable","exists:users,id"],
           "status"=>["sometimes",new Enum(TicketStatus::class)],
        ];
    }

  
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $ticket = $this->route('ticket');
            
            if ($this->has('status')) {
                $newStatus = TicketStatus::tryFrom($this->input('status'));
                
                if ($newStatus && !$ticket->canTransitionTo($newStatus)) {
                    $validator->errors()->add(
                        'status', 
                        "Changing the ticket status is not allowed.'{$ticket->status->value}' to '{$newStatus->value}'."
                    );
                }
            }
        });
    }
}
