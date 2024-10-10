<?php

namespace App\Http\Resources\Calendar;

use App\Models\Calendar\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin Event */
class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description ? Str::markdown($this->description) : null,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'all_day' => $this->all_day,
            'department' => $this->department,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'type' => new TypeResource($this->type),
            'responsible' => ResponsibleUserResource::collection($this->responsible),
        ];
    }
}
