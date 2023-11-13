<?php

namespace Hani221b\Grace\Support\Views;

class Create
{

    /**
     * Return the stub file path
     * @return string
     *
     */
    public static function getStubPath()
    {
        return __DIR__ . "/../../Stubs/views/create.stub";
    }

    /**
     **
     * Make create view for blade mode
     *
     * @return array
     *
     */
    public static function make($folder_name, $stubVariables)
    {
        $field_names = $stubVariables['field_names'];
        $inputs_types = $stubVariables['input_types'];
        $table_name = $stubVariables['table_name'];
        $select_options = $stubVariables['select_options'];
        $names_types_array = array_combine($field_names, $inputs_types);
        $template = array();
        foreach ($names_types_array as $field => $value) {
            switch ($value) {
                case 'text':
                    $input_template = self::input($field, $table_name);
                    break;

                case 'file':
                    $input_template = self::file($field, $table_name);
                    break;

                case 'textarea':
                    $input_template = self::textarea($field, $table_name);
                    break;

                case 'select':
                    foreach($select_options as $options){;
                        $input_template = self::select($field, $table_name, $options);
                    }
                    break;
                case 'relation':
                        $input_template = '';
                    break;
            }
            array_push($template, $input_template);
        }
        $string_input_template = '';
        foreach ($template as $index => $tem) {
            $string_input_template .= $template[$index] . "\n";
        }
        $contents = file_get_contents(self::getStubPath());

        $contents = str_replace('{{ inputs }}', $string_input_template, $contents);

        foreach ($stubVariables as $search => $replace) {

            if (!is_array($replace)) {
                $contents = str_replace('{{ ' . $search . ' }}', $replace, $contents);
            }
        }
        $path = base_path() . '/resources/views/' . config('grace.views_folder_name') . '/' . $folder_name;
        if (!file_exists($path)) {
            mkdir($path, 0700, true);
        }
        $file_name = $path . '/create.blade.php';
        file_put_contents($file_name, $contents);
    }

    /**
     * defining text input template
     * @param String $field
     * @return String
     */

    public static function input($field, $table_name)
    {
        $title = ucfirst($field);
        return "<div class='form-group'>
    <label for='{$field}'>
        <h5>{$title}</h5>
    </label>
    <input type='text' class='form-control input-default' placeholder='{$field}'
        name='{$table_name}[{{ \$index }}][{$field}]'>
        @error(\"$table_name.\$index.$field\")
        <p class='text-danger'>{{ \$message }}</p>
        @enderror
    </div>";
    }

    /**
     * defining textarea input template
     * @param String $field
     * @return String
     */

    public static function textarea($field, $table_name)
    {
        $title = ucfirst($field);
        return "<div class='form-group'>
        <label for='{$field}'>
            <h5>{$title}</h5>
        </label>
        <textarea class='form-control summernote' name='{$table_name}[{{ \$index }}][{$field}]'></textarea>
        @error(\"$table_name.\$index.$field\")
        <p class='text-danger'>{{ \$message }}</p>
        @enderror
    </div>";
    }

    /**
     * defining file input template
     * @param String $field
     * @return String
     */

    public static function file($field, $table_name)
    {
        $title = ucfirst($field);
        return "<div class='form-group'>
        <label for='{$field}'>
            <h5>{$title}</h5>
        </label>
        <input type='file' class='form-control' name='{$table_name}[{{ \$index }}][{$field}]'>
        @error(\"$table_name.\$index.$field\")
        <p class='text-danger'>{{ \$message }}</p>
        @enderror
    </div> ";
    }

    /**
     * defining file input template
     * @param String $field
     * @return String
     */

    public static function select($field, $table_name, $select_options)
    {
        $list_of_options = '';
        $options = explode(',', $select_options);
        foreach ($options as $option) {
            $list_of_options .= "<option value='$option'>" . $option . '</option>'. "\n";
        }
        $title = ucfirst($field);
        return "<div class='form-group'>
        <label>{$title}</label>
        <select class='form-control' name='{$table_name}[{{ \$index }}][{$field}]'>
          {$list_of_options}
        </select>
        @error(\"$table_name.\$index.$field\")
        <p class='text-danger'>{{ \$message }}</p>
        @enderror
    </div>";
    }
}
