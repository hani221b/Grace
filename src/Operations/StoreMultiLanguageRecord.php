<?php

namespace Hani221b\Grace\Operations;

use Hani221b\Grace\Support\Response;
use Hani221b\Grace\Support\File;
use Hani221b\Grace\Support\Lang;
use Illuminate\Support\Facades\DB;


class StoreMultiLanguageRecord
{
    /**
     * this function stores multi languages records in database bases on 'translation_of' key.
     * we first store the record in default language and get its id, then store translations
     * records and append 'translation_of' to the id of the default record
     * This function displays a listing of records that belong to certain model in default language
     *
     * @param array $records_from_request
     * @param string $model_path
     * @param array $fillable_values
     * @param array $files_fillable_vales
     * @param string $disk
     * @return \Illuminate\Http\JsonResponse
     */

    public static function store($records_from_request, $model_path, $fillable_values, $files_fillable_vales = null, $disk = null)
    {
        //get the request array and convert it to collection
        $records = collect($records_from_request);
        //filter the collection and get record
        $default_language_record_filter = $records->filter(function ($value) {
            return $value['abbr'] == Lang::GetDefaultLanguage();
        });
        //get record in default language
        $default_record = array_values($default_language_record_filter->all())[0];
        DB::beginTransaction();
        //declare empty array to push fillable values inside it
        $dynamic_fillable_values_array = [];
        //loop through incoming fillable values
        foreach ($fillable_values as $fillable_value) {
            $dynamic_fillable_values_array[$fillable_value] = $default_record[$fillable_value];
        }
        //check if request has any kind of file and merge it into the array that will be submitted
        $files_array = File::CheckKeyExists(
            $files_fillable_vales, $disk, $default_record
        );
        //define language_identification_values
        $translations_language_identification_values = array(
            'translation_lang' => $default_record['abbr'],
            'translation_of' => 0,
        );

        //merge constant fillable values with dynamic ones to store default language record
        $default_language_fillable_array = array_merge_recursive($translations_language_identification_values, $dynamic_fillable_values_array, $files_array);
        //store default language record and get its id
        $default_record_id = $model_path::insertGetId($default_language_fillable_array);
        //get records in other languages records
        $translations = $records->filter(function ($value) {
            return $value['abbr'] != Lang::GetDefaultLanguage();
        });

        $translations_fillable_array = [];
        //check if request has records in other languages
        if (isset($translations) && $translations->count() > 0) {
            //loop through the collection of translations
            foreach ($translations as $translation) {
                //loop through incoming fillable values
                foreach ($fillable_values as $translation_fillable_value) {
                    $dynamic_fillable_values_array[$translation_fillable_value] = $translation[$translation_fillable_value];
                }
                //check if request has any kind of file and merge it into the array that will be submitted
                $translations_files_array = File::CheckKeyExists(
                    $files_fillable_vales, $disk, $translation
                );
                //define language identification values for translations
                $translations_language_identification_values = array(
                    'translation_lang' => $translation['abbr'],
                    'translation_of' => $default_record_id,
                );
                //merge constant fillable values with dynamic ones to store translations records
                $translations_fillable_array[] = array_merge_recursive($translations_language_identification_values, $dynamic_fillable_values_array, $translations_files_array);
            }
            //store translations records
            $model_path::Insert($translations_fillable_array);
        }
        //submit the whole request
        DB::commit();
        //get the record in default language to load it in api
        $record_in_default_language = $model_path::where('id', $default_record_id)->first();

        if (config('grace.mode') === 'api') {
            return Response::success([
                'Record in default language' => $record_in_default_language,
                'translations' => $translations_fillable_array,
            ], 'The record has been stored successfully', 200);
        } else if (config('grace.mode') === 'blade') {
            return redirect()->route('grace.' . $disk. '.index');
            redirect('/' . $disk);
        }
    }
}
