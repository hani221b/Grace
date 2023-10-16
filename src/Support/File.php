<?php

namespace Hani221b\Grace\Support;

use Hani221b\Grace\Support\Str as GraceStr;
use Illuminate\Support\Str;
use Hani221b\Grace\Support\Stub;
use InvalidArgumentException;
class File
{
    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public static function sourceFilePath($namespace, $class_name, $suffix): string
    {
        return base_path($namespace) . '/' . GraceStr::singularClass($class_name) . $suffix . '.php';
    }

    /**
     * Get the full path of generate migration class
     *
     * @return string
     */
    public static function migrationSourceFilePath($namespace, $table_name): string
    {
        return base_path($namespace) . '/' . date("Y_m_d") . "_" . $_SERVER['REQUEST_TIME']
            . "_create_" . GraceStr::pluralLower($table_name) . "_table" . '.php';
    }

        /**
     * Build the directory for the class if necessary.
     *
     * @param  object  $files
     * @param  string  $path
     * @return string
     */
    public static function makeDirectory($files, $path): string
    {
        if (!$files->isDirectory($path)) {
            $files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

        /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public static function sourceFile($StubVariables, $type): mixed
    {
        return Stub::getStubContents(Stub::getStubPath($type), $StubVariables);
    }

        /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public static function migrationSourceFile($StubVariables, $type): mixed
    {
        return Stub::getMigrationStubContents(Stub::getStubPath($type), $StubVariables);
    }

        /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public static function modelSourceFile($StubVariables, $type): mixed
    {
        return Stub::getModelStubContents(Stub::getStubPath($type), $StubVariables);
    }

        /**
     * Delete a directory with its content
     * @param String dirPath
     * @return void
     */

    public static function deleteDir($dirPath): void
    {
        if (is_dir($dirPath)) {
                    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
          rmdir($dirPath);
        }

    }

        /**
     * Put the requested data inside the files have just created
     *
     * @param $files
     * @param string $path
     * @param string $contents
     * @return bool|mixed|string
     */
    public static function put($files, $path, $contents): mixed
    {
        if (!$files->exists($path)) {
            $files->put($path, $contents);
            // $this->info("File : {$path} created");
            return "File : {$path} created";
        } else {
            // $this->info("File : {$path} already exits");
            return "File : {$path} already exits";
        }
    }

    public static function upload($folder, $file)
    {
        // return var_dump($file);
        $file->store('/', $folder);
        $filename = $file->hashName();
        $path = $filename;
        return $path;
    }

    public static function unlink($file_from_request)
    {
        $file = Str::after($file_from_request, asset(''));
        $file_to_be_unlinked = base_path($file);
        if (file_exists($file_to_be_unlinked)) {
            unlink($file_to_be_unlinked);
        }
    }

    public static function checkKeyExists(
        $files_fillable_values,
        $disk = null,
        $collection_array = null,
        $unlink_collection = null
    ) {
        $file_array = [];
        $files_fillable_values = array_values($files_fillable_values);
        foreach ($files_fillable_values as $fillable_value) {
            if (in_array($fillable_value, array_keys( $collection_array))) {
                if (isset($unlink_collection)) {
                    self::unlink($unlink_collection[$fillable_value]);
                }
                // if(array_key_exists($fillable_value, $collection_array)){
                    $path = self::upload($disk, $collection_array[$fillable_value]);

                    $file_array = array_merge($file_array, [$fillable_value => $path]);
                // } else {
                //     $file_array = [];
                // }

            }
        }
        return $file_array;
    }


    public static function unlinkWhenDelete($files_fillable_values, $unlink_collection)
    {
        $files_fillable_values = array_values($files_fillable_values);
        foreach ($files_fillable_values as $fillable_value) {
            if (in_array($fillable_value, $files_fillable_values)) {
                self::unlink($unlink_collection[$fillable_value]);
            }
        }
    }
}
