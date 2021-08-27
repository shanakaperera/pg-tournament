<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PkgSeries extends Model
{
    use HasFactory;

    protected $table = 'pkg_series';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function tournaments()
    {
        return $this->belongsToMany(
            Tournament::class,
            'pkg_tourneys_series',
            'series_id',
            'tourney_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }
}
