<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PkgPrize extends Model
{
    use HasFactory;

    protected $table = 'pkg_prizes';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }
}
