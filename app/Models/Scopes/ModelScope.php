<?php

namespace App\Models\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ModelScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $year = session('year', Carbon::parse(now())->year);
        $trimestre = session('trimestre', "-1");
        if ($trimestre != null) {
            if ($trimestre == 0) $builder->whereRaw('(month(created_at) >= ?) and (month(created_at) <= ?)', [0, 2]);
            if ($trimestre == 1) $builder->whereRaw('(month(created_at) >= ?) and (month(created_at) <= ?)', [3, 5]);
            if ($trimestre == 2) $builder->whereRaw('(month(created_at) >= ?) and (month(created_at) <= ?)', [6, 9]);
            if ($trimestre == 3) $builder->whereRaw('(month(created_at) >= ?) and (month(created_at) <= ?)', [10, 12]);
        }
        if (
            $builder->getQuery()->from !== "paroissiens"
            &&
            $builder->getQuery()->from !== "associations"
            &&
            $builder->getQuery()->from !== "versements"
            &&
            $builder->getQuery()->from !== "cotisations"
            ) {

            if ($builder->getQuery()->from == "engagements") {
                // dd($builder);
                $builder->whereRaw('year(periode_start) = ?', $year);
            }else{
                $builder->whereRaw('year(created_at) = ?', $year);
            }
        }
        if ($builder->getQuery()->from == "cotisations") {
            $builder->whereRaw('for_year = ?', $year);
        }
    }
}
