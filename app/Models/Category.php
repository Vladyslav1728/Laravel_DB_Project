<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'color',
    ];

    public function scopeByName(Builder $query, string $name)
    {
        return $query->where('name', $name);
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'note_category')->withTimestamps();
    }
}
