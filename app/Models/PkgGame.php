<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PkgGame extends Model
{
    use HasFactory;

    protected $table = 'pkg_game_types';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('name_eng', 'like', '%' . $search . '%');
            });
        });
    }
}
