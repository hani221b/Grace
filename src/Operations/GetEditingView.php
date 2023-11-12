<?php

namespace Hani221b\Grace\Operations;

use Hani221b\Grace\Models\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GetEditingView
{
    /**
     * This function retuns a view in which we edit a multi-language record.
     * @param array $table_name
     */

    public static function edit($id, $table_name, $model_path)
    {
        $variable_name = Str::singular($table_name);
        $record = Str::singular($table_name);
        $record = $model_path::where('id', $id)->Selection()->first();
        $relations = Relation::where('local_table', $table_name)->get();

        $relations_array = [];
        foreach($relations as $relation){
            $relations_array[$relation->foreign_table] = DB::table($relation->foreign_table)->get();
        }
        return view(config('grace.views_folder_name') . '.' . $table_name . '.edit')->with([$variable_name => $record, ...$relations_array]);
    }
}
