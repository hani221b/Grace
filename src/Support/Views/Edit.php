<?php

namespace Hani221b\Grace\Support\Views;

use Illuminate\Support\Str;

class Edit
{

    /**
     * Return the stub file path
     * @return string
     *
     */
    public static function getStubPath()
    {
        return __DIR__ . "/../../Stubs/views/edit.stub";
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
        $select_options = $stubVariables['select_options'];
        $names_types_array = array_combine($field_names, $inputs_types);
        $contents = file_get_contents(self::getStubPath());
        //================================================================
        // Default language inputs
        //================================================================

        $template = array();
        foreach ($names_types_array as $field => $value) {
        $field_name = $folder_name . '[0]' . '[' . $field . ']';
        $error = "$folder_name.0.$field";
            switch ($value) {
                case 'text':
                    $input_template = self::input($folder_name, $field, Str::singular($folder_name), $field_name, $error);
                    break;

                case 'file':
                    $input_template = self::file($folder_name, $field, Str::singular($folder_name), $field_name, $error);
                    break;

                case 'textarea':
                    $input_template = self::textarea($folder_name, $field, Str::singular($folder_name), $field_name, $error);
                    break;

                case 'select':
                        $input_template = self::select($folder_name, $field, Str::singular($folder_name), $field_name, $error, $select_options);
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

        $contents = str_replace('{{ inputs }}', $string_input_template, $contents);

        //================================================================
        // Translations inputs
        //================================================================

        $translations_template = array();
        foreach ($names_types_array as $field => $value) {
        $translation_field_name = $folder_name . '[{{ $index }}]' . '[' . $field . ']';
        $translation_error = "$folder_name.\$index.$field";
            switch ($value) {
                case 'text':
                    $translation_input_template = self::input($folder_name, $field, 'translation', $translation_field_name, $translation_error);
                    break;

                case 'file':
                    $translation_input_template = self::file($folder_name, $field, 'translation',$translation_field_name, $translation_error);
                    break;

                case 'textarea':
                    $translation_input_template = self::textarea($folder_name, $field, 'translation', $translation_field_name, $translation_error);
                    break;
                case 'select':
                        $translation_input_template = self::select($folder_name, $field, "translation", $translation_field_name, $translation_error, $select_options);
                    break;
                    
            }
            array_push($translations_template, $translation_input_template);
        }

        $translations_string_input_template = '';
        foreach ($translations_template as $index => $tem) {
            $translations_string_input_template .= $translations_template[$index] . "\n";
        }

        $contents = str_replace('{{ translations_inputs }}', $translations_string_input_template, $contents);
        foreach ($stubVariables as $search => $replace) {

            if (!is_array($replace)) {
                $contents = str_replace('{{ ' . $search . ' }}', $replace, $contents);
            }
        }
        $path = base_path() . '/resources/views/' . config('grace.views_folder_name') . '/' . $folder_name;
        if (!file_exists($path)) {
            mkdir($path, 0700, true);
        }
        $file_name = $path . '/edit.blade.php';
        file_put_contents($file_name, $contents);
    }

    /**
     * defining text input template
     * @param String $key
     * @return String
     */

    public static function input($folder_name, $field, $key, $field_name, $error)
    {
        $title = ucfirst($field);

        return "<div class='form-group'>
        <label for='{$field}'>
        <h5>{$title}</h5>
        </label>
        <input type='text' class='form-control input-default' value='{{ $$key->$field }}' name='$field_name'>
        @error('$error')
        <p class='text-danger'>{{ \$message }}</p>
        @enderror
        </div>";
    }

    /**
     * defining textarea input template
     * @param String $key
     * @return String
     */

    public static function textarea($folder_name, $field, $key, $field_name, $error)
    {
        $title = ucfirst($field);
        return "<div class='form-group'>
        <label for='{$field}'>
            <h5>{$title}</h5>
        </label>
        <textarea class='form-control summernote' name='$field_name'>{{ $$key->$field }}</textarea>
        @error('$error')
        <p class='text-danger'>{{ \$message }}</p>
        @enderror
        </div>";
    }

    /**
     * defining file input template
     * @param String $key
     * @return String
     */

    public static function file($folder_name, $field, $key, $field_name, $error)
    {
        $title = ucfirst($field);
        return "<div class='form-group'>
        <label for='{$field}'>
            <h5>{$title}</h5>
        </label>
        <img src='{{ $$key->$field }}'  width='200px'>
        <input type='file' class='form-control' name='$field_name'>
        @error('$error')
        <p class='text-danger'>{{ \$message }}</p>
        @enderror
        </div>";

    }

    /**
     * defining file input template
     * @param String $field
     * @return String
     */

    public static function select($table_name, $field,  $key, $field_name, $error, $select_options)
    {

        $list_of_options = '';
        $options = explode(',', $select_options[0]);
        foreach ($options as $option) {
            $list_of_options .= "<option {{ ( \$$key->$field == '$option') ? 'selected' : '' }} value='$option'>" . $option . '</option>' . "\n";
        }
        $title = ucfirst($table_name);
        return "<div class='form-group'>
        <label>{$title}</label>
        <select class='form-control' name='$field_name'>
          {$list_of_options}
        </select>
        @error('$error')
        <p class='text-danger'>{{ \$message }}</p>
        @enderror
    </div>";
    }
}
