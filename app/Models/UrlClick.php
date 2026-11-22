<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UrlClick extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'url_id',
        'clicked_at',
    ];

    protected function casts(): array
    {
        return [
            'clicked_at' => 'datetime',
        ];
    }

    public function url(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }
}
