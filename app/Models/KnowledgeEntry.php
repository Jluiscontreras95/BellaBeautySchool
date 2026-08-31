<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KnowledgeEntry extends Model
{
    protected $fillable = [
        'category',
        'title',
        'content',
        'embedding',
        'metadata',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'embedding' => 'array',
            'metadata' => 'array',
            'active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Texto que se incrusta (embedding) para la búsqueda semántica.
     */
    public function searchableText(): string
    {
        return trim("{$this->title}\n{$this->content}");
    }
}
