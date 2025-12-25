<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\AcademicYear;

class ActiveYearScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        // Only apply if we are not explicitly asking for all history
        // And if there is an active year set in the system
        if ($activeYear = AcademicYear::current()) {
            $builder->where('academic_year_id', $activeYear->id);
        }
    }
}
