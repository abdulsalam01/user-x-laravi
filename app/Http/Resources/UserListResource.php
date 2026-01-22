<?php

namespace App\Http\Resources;

use App\Support\CanEditUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $actor = $request->user();

        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'role' => $this->role,
            'created_at' => $this->created_at?->toISOString(),
            'orders_count' => (int) ($this->orders_count ?? 0),
            'can_edit' => $actor ? CanEditUser::check($actor, $this->resource) : false,
        ];
    }
}
