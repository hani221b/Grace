<?php

namespace Hani221b\Grace\Operations;
use App\Models\Category;
use App\Models\Relation;
use Illuminate\Support\Facades\DB;

class GetCreationView
{
    /**
     * This function retuns a view in which we create a multi-language record.
     * @param array $table_name
     */

    public static function create($table_name)
    {
        $relations = Relation::where('local_table', $table_name)->get();

        $relations_array = [];
        foreach($relations as $relation){
            $relations_array[$relation->foreign_table] = DB::table($relation->foreign_table)->get();
        }
        return view(config('grace.views_folder_name'). '.' . $table_name . '.create')->with($relations_array) ;
    }
}
