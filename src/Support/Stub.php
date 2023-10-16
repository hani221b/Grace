<?php

namespace Hani221b\Grace\Support;

use Hani221b\Grace\Support\Factory;
class Stub
{
    /**
     * Return the stub file path
     * @return string
     *
     */
    public static function getStubPath($type): string
    {
        return __DIR__ . "/../Stubs/{$type}.stub";
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public static function getStubContents($stub, $stubVariables = []): mixed
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {

            $contents = str_replace('{{ ' . $search . ' }}', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public static function getModelStubContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        $string_mutators_template = Factory::appendMutatorToModel($stubVariables);

        if ($stubVariables['files_fields'] === null) {
            $contents = str_replace('{{ mutatators }}', "", $contents);
        } else {
            $contents = str_replace('{{ mutatators }}', $string_mutators_template, $contents);
        }

        foreach ($stubVariables as $search => $replace) {

            $contents = str_replace('{{ ' . $search . ' }}', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public static function getMigrationStubContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        $field_names = $stubVariables['field_names'];
        $field_types = $stubVariables['field_types'];

        $tabels_types_and_names = array_combine($field_names, $field_types);

        unset($stubVariables["field_names"]);
        unset($stubVariables["field_types"]);

        $template = array();

        foreach ($tabels_types_and_names as $key => $value) {
            array_push($template, "table->$value('$key')");
        }
        $tables_template = '';
        foreach ($template as $index => $tem) {
            $tables_template .= "$" . $template[$index] . ";" . "\n";
        }

        $contents = str_replace('{{ content }}', $tables_template, $contents);

        foreach ($stubVariables as $search => $replace) {

            $contents = str_replace('{{ ' . $search . ' }}', $replace, $contents);
        }

        return $contents;
    }


}
