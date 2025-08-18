<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait HandlesSearches
{
    /**
     * @param Request $request
     * @param Builder $query
     * @param string[] $columns
     * @return LengthAwarePaginator
     */
    public function applySearchConditions(Request $request, Builder $query, array $columns): LengthAwarePaginator
    {
        $search = $request->query('search', '');

        $query->where(function (Builder $query) use ($columns, $search) {
            foreach ($columns as $column) {
                $query->orWhereLike($column, "%$search%");
            }
        });

        $paginator = $query->paginate($request->query('per_page', 10));

        foreach ($request->query() as $key => $value) {
            $paginator->appends($key, $value);
        }

        return $paginator;
    }
}
