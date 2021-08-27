<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $table = 'pkg_tourneys';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $casts = [
        'start_date'   => 'datetime:Y-m-d H:i:s',
        'online'       => 'boolean',
        'exclude_list' => 'boolean',
    ];

    protected $dates = ['start_date'];

    protected $fillable = [
        'tourneyname', 'start_date', 'series_id', 'structure_type_id', 'game_type_id', 'limit_type_id', 'prize_id',
        'grade_id', 'maxseats', 'online', 'exclude_list', 'start_chips', 'entry_fee', 'entry_prize', 'rebuy', 'rebuy_chips',
        'rebuy_fee', 'rebuy_price', 'addon', 'addon_chips', 'addon_fee', 'addon_prize', 'reentry', 'reentry_chips', 'reentry_fee',
        'reentry_prize', 'buyin_policy', 'url', 'entries', 'comment'
    ];

    public function gameType()
    {
        return $this->belongsTo(PkgGame::class);
    }

    public function structureType()
    {
        return $this->belongsTo(Structure::class);
    }

    public function limitType()
    {
        return $this->belongsTo(PkgLimit::class);
    }

    public function prize()
    {
        return $this->belongsTo(PkgPrize::class);
    }

    public function grade()
    {
        return $this->belongsTo(PkgGrade::class);
    }

    public function series()
    {
        return $this->belongsToMany(
            PkgSeries::class,
            'pkg_tourneys_series',
            'tourney_id',
            'series_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('tourneyname', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        });
    }

    //calendar filtering
    public function scopeFilterCal($query, array $filters)
    {
        $start = date('Y-m-d', strtotime($filters['start']));
        $end = date('Y-m-d', strtotime($filters['end']));

        $query->where(function ($query) use ($start, $end) {
            $query->whereBetween('start_date', [$start, $end]);
        });
    }
}
