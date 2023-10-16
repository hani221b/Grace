<?php

namespace Hani221b\Grace\Support\Views;

class Sidebar
{
    public static function append($stubVariables = [])
    {
        $table_name = $stubVariables['table_name'];
        $single_record_table = $stubVariables['single_record_table'];
        if ($single_record_table === null) {
            $row = self::regualr_table($table_name);
        } else if ($single_record_table === "1") {
            $row = self::single_record_table($table_name);
        }

        $filename = base_path() . '/resources/views/grace/includes/sidebar.blade.php';
        $line_i_am_looking_for = 4;
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $lines[$line_i_am_looking_for] = "\n" . $row;
        file_put_contents($filename, implode("\n", $lines));
        return false;
    }

    /**
     * Define the template of a sidebar row for regular table
     */

    public static function regualr_table($table_name)
    {
        $label = ucfirst($table_name);
        return "
        <!--<$table_name>-->
        <li>
            <a class='has-arrow' href='javascript:void()' aria-expanded='false'>
                <i class='icon-speedometer menu-icon'></i><span class='nav-text'>$label</span>
            </a>
            <ul aria-expanded='false'>
                <li><a href='{{ route('grace.$table_name.index') }}'>Index</a></li>
                <li><a href='{{ route('grace.$table_name.create') }}'>Create</a></li>
                <li><a href='{{ route('grace.$table_name.sort') }}'>Sort</a></li>
            </ul>
        </li>
        <!--</$table_name>-->
     ";
    }

    /**
     * Define the template of a sidebar row for single record table
     */

    public static function single_record_table($table_name)
    {
        $label = ucfirst($table_name);
        return "
        <!--<$table_name>-->
        <li>
            <a class='has-arrow' href='{{ route('grace.$table_name.index') }}' aria-expanded='false'>
                <i class='icon-speedometer menu-icon'></i><span class='nav-text'>$label</span>
            </a>
        </li>
        <!--</$table_name>-->
     ";
    }
}
