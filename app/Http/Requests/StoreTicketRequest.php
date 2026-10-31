<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           "title" =>['required','string','max:255'],
           "description" =>['required','text'],
           "priority" =>['sometimes',Rule::in(['low','medium','high','urgent'])],
           'status' => ['required', Rule::in(['open', 'in_progress', 'resolved', 'closed'])],
        ];
    }
}
