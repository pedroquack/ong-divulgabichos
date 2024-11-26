<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ong extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
