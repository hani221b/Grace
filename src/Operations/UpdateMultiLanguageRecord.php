<?php

namespace Hani221b\Grace\Operations;

use Hani221b\Grace\Support\Response;
use Hani221b\Grace\Support\File;

class UpdateMultiLanguageRecord
{
    /**
     * this function update multi languages records in database bases on 'id' key.
     *
     * @param int $id
     * @param array $record_from_request
     * @param string $model_path
     * @param array $fillable_values
     * @param array $files_fillable_vales
     * @param string $disk
     * @param string $file_path
     * @return \Illuminate\Http\JsonResponse
     */

    public static function update(
        $id,
        $record_from_request,
        $model_path,
        $fillable_values,
        $files_fillable_values = null,
        $disk = null
    ) {

        //fetch the requested record
        $requested_record = $model_path::find($id);
        //return false if requested was not found
        if (!$requested_record) {
            return Response::error('The requested record does not exist', 404);
        }
        //fetch translations
        $translations = $requested_record->translations;
        //get record array and return all its values
        $record_and_translations = array_values($record_from_request);
        //loop through record in default language and its translations
        foreach ($record_and_translations as $record) {
            //declare empty array to push fillable values inside it
            $dynamic_fillable_values_array = [];
            //loop through incoming fillable values
            //check if request has any kind of file and merge it into the array that will be submitted
           $files_fillable_array = File::CheckKeyExists(
            $files_fillable_values,
            $disk,
            $record,
            $requested_record
            );

            foreach ($fillable_values as $fillable_value) {
                $dynamic_fillable_values_array[$fillable_value] = $record[$fillable_value];
            }
            $dynamic_fillable_values_array = array_merge($dynamic_fillable_values_array, $files_fillable_array);
            //update translations
            foreach ($translations as $translation) {
                //check if request has any kind of file and merge it into the array that will be submitted (translations)
                $translations_files_array = File::CheckKeyExists(
                    $files_fillable_values,
                    $disk,
                    $record,
                    $translation
                );

                $dynamic_fillable_values_array = array_merge(
                    $dynamic_fillable_values_array,
                    $translations_files_array
                );
            }
            //define the id for every record
            $record_id = $record['id'];
            //update the whole request
            $model_path::where('id', $record_id)->update($dynamic_fillable_values_array);

            $all_records = $requested_record->with('translations')->first();
        }
        if (config('grace.mode') === 'api') {
            return Response::success(
                $all_records,
                'The record has been updated successfully',
                200
            );
        } else if (config('grace.mode') === 'blade') {
            return redirect()->route('grace.' . $disk . '.index');
        }
    }
}
