<?php

namespace Hani221b\Grace\Support\Views;

use Hani221b\Grace\Support\Stub;

class Sidebar
{
    public static function append($stubVariables = [])
    {
 
        $table_name = $stubVariables['table_name'];
        // $single_record_table = $stubVariables['single_record_table'];
        // if ($single_record_table === null) {
            $row = self::sidebarItemTemplate($table_name);
        // } else if ($single_record_table === "1") {
        //     $row = self::singleRecordTemplate($table_name);
        // }

        $filename = base_path()  . '/resources/views/grace/includes/sidebar.blade.php';
        $line_i_am_looking_for = 17;
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $lines[$line_i_am_looking_for] = "\n" . $row;
        file_put_contents($filename, implode("\n", $lines));
        return false;
    }

    public static function sidebarItemTemplate($table_name)
    {
        $label = ucfirst($table_name);
        $stubVariables = [
            "TABLE_NAME" => $table_name,
            "LABEL" => $label
        ];
       return Stub::getStubContents(__DIR__ . "../../../Stubs/dashboard/sidebar.stub", $stubVariables);
    }

    // public static function singleRecordTemplate($table_name)
    // {
    //     $label = ucfirst($table_name);
    //     $stubVariables = [
    //         "TABLE_NAME" => $table_name,
    //         "LABEL" => $label
    //     ];
    //    return Stub::getStubContents(__DIR__ . "../../../Stubs/dashboard/sidebar-item-regular.stub", $stubVariables);
    // }
}
