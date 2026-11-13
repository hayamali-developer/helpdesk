<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        "id"=> $this->id,
        "title"=>$this->title,
        "description"=>$this->description,
        "priority" =>$this->priority,
        "status" =>$this->status,
        'requester' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'assignee' => $this->assignee ? [
                'id' => $this->assignee->id,
                'name' => $this->assignee->name,
            ] : null,
        "created_by"=>(string)$this->created_at->format("Y-m-d H:i:s"),
        ];
    }
}
