<?php

namespace App\Http\Requests;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Ticket;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('ticket'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assignee_id' => [
                'sometimes',
                'nullable',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->whereIn('role', UserRole::managementRoles())
                ),
            ],
            'status' => [
                'sometimes',
                new Enum(TicketStatus::class),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->has('status')) {
                return;
            }

        
            $ticket = $this->route('ticket');
            $newStatus = TicketStatus::tryFrom((string) $this->input('status'));

            if ($newStatus && ! $ticket->canTransitionTo($newStatus)) {
                $validator->errors()->add(
                    'status',
                    "Changing the ticket status from '{$ticket->status->value}' to '{$newStatus->value}' is not allowed."
                );
            }
        });
    }
}