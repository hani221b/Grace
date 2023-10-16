<?php

namespace Hani221b\Grace\Support\Views;

use Illuminate\Support\Str;

class Index
{
    /**
     * Return the stub file path
     * @return string
     *
     */
    public static function getStubPath()
    {
        return __DIR__ . "/../../Stubs/views/index.stub";
    }

    /**
     **
     * Make create view for blade mode
     *
     * @return array
     *
     */
    public static function make($table_name, $stubVariables)
    {
        // dd($table_name);
        $field_names = $stubVariables['field_names'];
        $inputs_types = $stubVariables['input_types'];
        // dd($inputs_types);
        $names_types_array = array_combine($field_names, $inputs_types);
        $key = Str::singular($table_name);
        $th_template = array();
        $tr_template = array();

        $contents = file_get_contents(self::getStubPath());
        //===============================================================
        // Td fields
        //===============================================================

        foreach ($names_types_array as $name => $type) {
            $label = Str::ucfirst($name);
            $th_fields_template = "<th>$label</th>";
            array_push($th_template, $th_fields_template);
        }

        $string_th_fields_template = '';
        foreach ($th_template as $index => $tem) {
            $string_th_fields_template .= $th_template[$index] . "\n";
        }

        $contents = str_replace('{{ th_fields }}', $string_th_fields_template, $contents);

        //===============================================================
        // Tr fields
        //===============================================================

        foreach ($names_types_array as $name => $type) {
            if ($type === 'file') {
                $td_fields_template = "  <th><img src='{{ $$key->$name }}' width='100px' /></th>";
            } else {
                $td_fields_template = " <td>{{ $$key->$name }}</td>";
            }

            array_push($tr_template, $td_fields_template);
        }

        $string_td_fields_template = '';
        foreach ($tr_template as $index => $tem) {
            $string_td_fields_template .= $tr_template[$index] . "\n";
        }


        $contents = str_replace('{{ td_fields }}', $string_td_fields_template, $contents);
        // dd($contents);
        foreach ($stubVariables as $search => $replace) {

            if (!is_array($replace)) {
                $contents = str_replace('{{ ' . $search . ' }}', $replace, $contents);
            }
        }
        $path = base_path() . '/resources/views/' . config('grace.views_folder_name') . '/' . $table_name;
        if (!file_exists($path)) {
            mkdir($path, 0700, true);
        }
        $file_name = $path . '/index.blade.php';
        file_put_contents($file_name, $contents);
    }
}
