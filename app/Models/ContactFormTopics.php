<?php

namespace App\Models;

use IbrahimBougaoua\FilamentSortOrder\Traits\SortOrder;
use Illuminate\Database\Eloquent\Model;

class ContactFormTopics extends Model
{
    use SortOrder;

    protected $fillable = [
        'name',
        'sort_order',
        'to',
        'cc',
    ];

    protected function casts(): array
    {
        return [
            'to' => 'array',
            'cc' => 'array',
        ];
    }

    public static function getTopics(): array
    {
        $topics = self::select('name')->get();

        return $topics->pluck('name')->toArray();
    }
}
