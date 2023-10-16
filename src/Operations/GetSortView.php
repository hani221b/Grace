<?php

namespace Hani221b\Grace\Operations;

use Illuminate\Support\Str;

class GetSortView
{
    /**
     * This function retuns a view in which we edit a multi-language record.
     * @param array $table_name
     */

    public static function sort($id, $table_name, $model_path)
    {
        $variable_name = Str::singular($table_name);
        $records = $model_path::Selection()->get();
        return view(config('grace.views_folder_name') . '.' . $table_name . '.sort')->with([$variable_name => $records]);
    }
}
