<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    protected $fillable = ['name'];
    public function brands()
{
    return $this->belongsToMany(Brand::class);
}
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
