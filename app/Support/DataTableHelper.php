<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Lightweight server-side processing helper for jQuery DataTables.
 * No external package required - works directly with an Eloquent builder.
 *
 * Usage:
 *   return DataTableHelper::respond($request, $query, [
 *       'name'   => 'name',
 *       'email'  => 'email',
 *       'status' => 'status',
 *   ], function ($row) {
 *       return [ ...formatted row... ];
 *   });
 */
class DataTableHelper
{
    public static function respond(Request $request, Builder $query, array $searchableColumns, callable $formatter)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $searchValue = $request->input('search.value');
        $orderColIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'desc');
        $columns = $request->input('columns', []);

        $recordsTotal = (clone $query)->count();

        if (! empty($searchValue)) {
            $query->where(function ($q) use ($searchableColumns, $searchValue) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$searchValue}%");
                }
            });
        }

        $recordsFiltered = (clone $query)->count();

        if ($orderColIndex !== null && isset($columns[$orderColIndex]['data'])) {
            $orderColumn = $columns[$orderColIndex]['data'];
            if (in_array($orderColumn, $searchableColumns, true)) {
                $query->orderBy($orderColumn, $orderDir === 'asc' ? 'asc' : 'desc');
            }
        }

        $records = $length == -1
            ? $query->get()
            : $query->skip($start)->take($length)->get();

        $data = $records->map($formatter)->values();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
