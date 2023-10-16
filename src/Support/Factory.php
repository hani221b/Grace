<?php

namespace Hani221b\Grace\Support;

use Hani221b\Grace\Support\Str as GraceStr;
use Illuminate\Support\Str;
class Factory
{
    public static function appendDisk($stubVariables = [])
    {
        $table_name = $stubVariables['table_name'];
        // $storage_path = $stubVariables['storage_path'];
        $disk = "
        /*<$table_name-disk>*/
        '$table_name' => [
            'driver' => 'local',
            'root' => public_path() . '/grace/storage/$table_name' ,
            'url' => env('APP_URL') . '/',
            'visibility' => 'public',
        ],
        /*</$table_name-disk>*/
        ";

        $filename = base_path() . '/config/filesystems.php';
        $file_content = file_get_contents($filename);
        $file_content = str_replace("'disks' => [",
            " 'disks' => [ \r\n". $disk, $file_content);
        file_put_contents($filename, $file_content);
    }

        /**
     * looping through an array of files fields and return mutators template
     * @param array stubVariables
     * @return string
     */
    public static function appendMutatorToModel($stubVariables)
    {
        $template = array();
        $table_name = $stubVariables['table_name'];
        $files_fileds = $stubVariables['files_fields'];
        $files_array = explode(',', $files_fileds);
        foreach ($files_array as $value) {
            $name =  str_replace("'", "", Str::title($value));
            $mutators_names =  "get${name}Attribute";
            //remove spaces if exist
            $mutators_names =   str_replace(" ", "", $mutators_names);
            $mutator_template = "public function $mutators_names(\$value)
    {
        return (\$value !== null) ? asset('grace/storage/$table_name/' . \$value) : '';
    }
    ";

            array_push($template, $mutator_template);
        }

        $string_mutators_template = '';
        foreach ($template as $index => $tem) {
            $string_mutators_template .= $template[$index] . "\n";

        }
        return $string_mutators_template;
    }

    /**
     * Mapping the value of field names and files fields
     * @return string
     */

    public static function modelFillableArray($field_names)
    {
        return "'" . str_replace(",", "', '", implode(",", $field_names)) . "'";
    }

        /**
     * returns the path of the requested route file
     * @return string
     */
    public static function getRouteFileName()
    {
        if (config('grace.mode') === 'api') {
            $filename = base_path() . '/routes/api.php';
        } else if (config('grace.mode') === 'blade') {
            $filename = base_path() . '/routes/grace.php';
        }
        return $filename;
    }

    /**
     * append use controller statement at the to of the route file
     * @param @stubVariables
     * @param @controller_name
     * @return void
     */
    public static function appendUseController($stubVariables = [], $controller_name)
    {
        $controller_namespace = $stubVariables['controller_namespace'];
        $table_name = $stubVariables['table_name'];
        $use_controller = "
/*<$table_name-controller>*/
use $controller_namespace\\$controller_name;
/*</$table_name-controller>*/
";
        $filename = self::getRouteFileName();
        $line_i_am_looking_for = 1;
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $lines[$line_i_am_looking_for] = $use_controller;
        file_put_contents($filename, implode("\n", $lines));
    }
    /**
     * append resource routes for a certain table
     * @param $stubVariables
     * @return void
     */

    public static function appendRoutes($stubVariables = [])
    {
        $routes_file = self::getRouteFileName();
        $opened_file = fopen($routes_file, 'a');
        $controller_name = GraceStr::singularClass($stubVariables['table_name']) . "Controller";
        $table_name = $stubVariables['table_name'];
        $routes_template = "
/*<$table_name-routes>*/
Route::resource('$table_name', $controller_name::class, ['as' => 'grace']);
/*</$table_name-routes>*/
";

        self::appendUseController($stubVariables, $controller_name);
        fwrite($opened_file, $routes_template);
        fclose($opened_file);
    }
}
