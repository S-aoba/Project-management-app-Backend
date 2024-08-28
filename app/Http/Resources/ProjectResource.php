<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'dueDate' => (new Carbon($this->due_date))->format('Y-m-d'),
            'status' => $this->status,
            'imagePath' => $this->image_path,
            'createdBy' => $this->created_by,
            'updatedBy' => $this->updated_by,
            'createdAt' => (new Carbon($this->created_at))->format('Y-m-d'),
            'updatedAt' => (new Carbon($this->updated_at))->format('Y-m-d')
        ];
    }
}