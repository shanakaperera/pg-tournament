<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\PkgGame;
use App\Models\PkgGrade;
use App\Models\PkgLimit;
use App\Models\PkgPrize;
use App\Models\PkgSeries;
use App\Models\Structure;
use App\Models\Tournament;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class TournamentsController extends Controller
{
    public function index()
    {
        return Inertia::render('Tournaments/Index', [
            'filters'     => Request::all('search'),
            'tournaments' => Tournament::orderBy('id', 'DESC')
                ->where('venue_id', auth()->id())
                ->filter(Request::only('search'))
                ->paginate(20)
                ->withQueryString()
                ->through(function ($tournament) {
                    return [
                        'id'          => $tournament->id,
                        'tourneyname' => $tournament->tourneyname,
                        'place'       => $tournament->place,
                        'datetime'    => Carbon::parse($tournament->start_date)->format('Y-m-d H:i'),
                    ];
                }),
        ]);
    }

    public function series()
    {
        return response()->json([
            'series' => PkgSeries::orderBy('id')
                ->where('venue_id', auth()->id())
                ->filter(Request::only('search'))
                ->take(20)
                ->get()
                ->map(function ($series) {
                    return [
                        'id'   => $series->id,
                        'name' => $series->name,
                    ];
                })->toArray()
        ]);

    }

    public function structures()
    {
        return response()->json([
            'structures' => Structure::orderBy('id')
                ->where('venue_id', auth()->id())
                ->filter(Request::only('search'))
                ->take(20)
                ->get()
                ->map(function ($structure) {
                    return [
                        'id'   => $structure->id,
                        'name' => $structure->name,
                    ];
                })->toArray()
        ]);

    }

    public function games()
    {
        return response()->json([
            'games' => PkgGame::orderBy('id')
                ->filter(Request::only('search'))
                ->take(20)
                ->get()
                ->map(function ($game) {
                    return [
                        'id'   => $game->id,
                        'name' => $game->name,
                    ];
                })->toArray()
        ]);

    }

    public function limits()
    {
        return response()->json([
            'limits' => PkgLimit::orderBy('id')
                ->filter(Request::only('search'))
                ->take(20)
                ->get()
                ->map(function ($limit) {
                    return [
                        'id'   => $limit->id,
                        'name' => $limit->name_eng,
                    ];
                })->toArray()
        ]);

    }

    public function prizes()
    {
        return response()->json([
            'prizes' => PkgPrize::orderBy('id')
                ->where('venue_id', auth()->id())
                ->filter(Request::only('search'))
                ->take(20)
                ->get()
                ->map(function ($prize) {
                    return [
                        'id'   => $prize->id,
                        'name' => $prize->name,
                    ];
                })->toArray()
        ]);

    }

    public function grades()
    {
        return response()->json([
            'grades' => PkgGrade::orderBy('id')
                ->filter(Request::only('search'))
                ->take(20)
                ->get()
                ->map(function ($grade) {
                    return [
                        'id'   => $grade->id,
                        'name' => $grade->name_eng,
                    ];
                })->toArray()
        ]);

    }

    public function tournaments()
    {
        return response()->json(Tournament::orderBy('id')
            ->filterCal(Request::only(['start', 'end']))
            ->get()
            ->map(function ($tournament) {
                return [
                    'url'   => route('tournaments.edit', $tournament->id),
                    'title' => $tournament->tourneyname,
                    'place' => $tournament->place,
                    'date'  => Carbon::parse($tournament->start_date)->format('Y-m-d'),
                ];
            })->toArray()
        );
    }

    public function create()
    {
        return Inertia::render('Tournaments/Create');
    }

    public function store()
    {
        DB::transaction(function () {

            $tournament = Tournament::create(array_merge($this->formData(), ['venue_id' => auth()->id()]));

            $series = [];

            foreach (request()->get('series_id') as $s) {
                $series[] = ['series_id' => $s['id']];
            }

            if (!empty($series)) {
                $tournament->series()->sync($series);
            }
        });

        return Redirect::route('tournaments')->with('success', 'Tournament created successfully!.');
    }

    public function edit(Tournament $tournament)
    {
        return Inertia::render('Tournaments/Edit', [
            'tournament' => [
                'id'                => $tournament->id,
                'tourneyname'       => $tournament->tourneyname,
                'place'             => $tournament->place,
                'start_date'        => Carbon::parse($tournament->start_date)->format('Y-m-d H:i'),
                'series_id'         => $tournament->series,
                'structure_type_id' => $tournament->structureType,
                'game_type_id'      => $tournament->gameType,
                'limit_type_id'     => $tournament->limitType,
                'prize_id'          => $tournament->prize,
                'grade_id'          => $tournament->grade,
                'maxseats'          => $tournament->maxseats . '',
                'online'            => $tournament->online,
                'exclude_list'      => $tournament->exclude_list,
                'start_chips'       => $tournament->start_chips . '',
                'entry_fee'         => $tournament->entry_fee . '',
                'entry_prize'       => $tournament->entry_prize . '',
                'rebuy'             => $tournament->rebuy . '',
                'rebuy_chips'       => $tournament->rebuy_chips . '',
                'rebuy_fee'         => $tournament->rebuy_fee . '',
                'rebuy_prize'       => $tournament->rebuy_prize . '',
                'addon'             => $tournament->addon . '',
                'addon_chips'       => $tournament->addon_chips . '',
                'addon_fee'         => $tournament->addon_fee . '',
                'addon_prize'       => $tournament->addon_prize . '',
                'reentry'           => $tournament->reentry . '',
                'reentry_chips'     => $tournament->reentry_chips . '',
                'reentry_fee'       => $tournament->reentry_fee . '',
                'reentry_prize'     => $tournament->reentry_prize . '',
                'buyin_policy'      => $tournament->buyin_policy,
                'url'               => $tournament->url,
                'entries'           => $tournament->entries . '',
                'comment'           => $tournament->comment,
            ],
        ]);
    }

    public function update(Tournament $tournament)
    {
        DB::transaction(function () use ($tournament) {

            $series = [];

            foreach (request()->get('series_id') as $s) {
                $series[] = ['series_id' => $s['id']];
            }

            $tournament->update($this->formData());
            if (!empty($series)) {
                $tournament->series()->sync($series);
            }
        });

        return Redirect::back()->with('success', 'Tournament updated successfully.');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();

        return Redirect::back()->with('success', 'Tournament deleted.');
    }

    private function formData()
    {

        return Request::validate([
            'tourneyname'       => ['required', 'max:100'],
            'place'             => ['nullable', 'string'],
            'start_date'        => ['required', 'date_format:Y-m-d H:i'],
            // 'series_id'         => ['nullable'],
            'structure_type_id' => ['required', 'integer'],
            'game_type_id'      => ['required', 'integer'],
            'limit_type_id'     => ['required', 'integer'],
            'prize_id'          => ['required', 'integer'],
            'grade_id'          => ['required', 'integer'],
            'maxseats'          => ['required', 'integer', 'min:1'],
            'online'            => ['nullable', 'boolean'],
            'exclude_list'      => ['nullable', 'boolean'],
            'start_chips'       => ['nullable', 'integer'],
            'entry_fee'         => ['nullable', 'integer'],
            'entry_prize'       => ['nullable', 'integer'],
            'rebuy'             => ['nullable', 'integer'],
            'rebuy_chips'       => ['nullable', 'integer'],
            'rebuy_fee'         => ['nullable', 'integer'],
            'rebuy_prize'       => ['nullable', 'integer'],
            'addon'             => ['nullable', 'integer'],
            'addon_chips'       => ['nullable', 'integer'],
            'addon_fee'         => ['nullable', 'integer'],
            'addon_prize'       => ['nullable', 'integer'],
            'reentry'           => ['nullable', 'integer'],
            'reentry_chips'     => ['nullable', 'integer'],
            'reentry_fee'       => ['nullable', 'integer'],
            'reentry_prize'     => ['nullable', 'integer'],
            'buyin_policy'      => ['nullable', 'string'],
            'url'               => ['nullable', 'string'],
            'entries'           => ['nullable', 'integer'],
            'comment'           => ['nullable', 'string'],
        ]);
    }
}
