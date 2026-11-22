<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model
{
    use HasUuids, SoftDeletes;
    
    protected $table = 'companies';

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function urls(): HasMany
    {
        return $this->hasMany(Url::class);
    }

    public function urlClicks(): HasManyThrough
    {
        return $this->hasManyThrough(UrlClick::class, Url::class, 'company_id', 'url_id', 'id', 'id');
    }
}
