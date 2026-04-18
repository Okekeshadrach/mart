<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->relationLoaded('user') ? $this->user : null;

        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'userName' => $user?->name,
            'rating' => (int) $this->rating,
            'comment' => $this->comment,
            'createdAt' => $this->created_at?->toISOString(),
        ];
    }
}
