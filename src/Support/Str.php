<?php

namespace Hani221b\Grace\Support;

use Illuminate\Support\Pluralizer;

class Str
{
    /**
     * Return the Singular Capitalize Name
     * @param $class_name
     * @return string
     */
    public static function singularClass($class_name): string
    {
        return ucwords(Pluralizer::singular($class_name));
    }

    /**
     * Return the PLural Lower Case Name
     * @param $table_name
     * @return string
     */
    public static function pluralLower($table_name): string
    {
        return strtolower(Pluralizer::plural($table_name));
    }

    /**
     * Gets a string between two characters. Used to delete or modefiy the code
     * @param String string
     * @param String start
     * @param String end
     * @return String
     */

    public static function getBetween($string, $start, $end): string
    {
        $string = " " . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return "";
        }

        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /**
     * Undocumented function
     *
     * @param String $namespace
     * @return string
     */
    public static function namespaceCorrection($namespace): string
    {
        $namespace = str_replace("app", "App", $namespace);
        $namespace = str_replace("/", "\\", $namespace);
        return $namespace;
    }

    /**
     * Remove All extra spaces from a string
     *
     * @param String $str
     * @return string
     */
    public static function stripString($str): string
    {
        return trim(preg_replace('/[\t\n\r\s]+/', ' ', $str));
    }
}
