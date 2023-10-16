<?php

namespace Hani221b\Grace\Operations;

use Hani221b\Grace\Support\File;
use Hani221b\Grace\Support\Response;

class DeleteMultiLanguageRecord
{
    /**
     * this function delete multi language record and unlink its files.
     *
     * @param int $id
     * @param string $model_path
     * @return \Illuminate\Http\Response
     */

    public static function delete(
        $id,
        $model_path,
        $files_fillable_vales = null,
        $table_name,
    ) {
        //fetch the requested record
        $requested_record = $model_path::withTrashed()->find($id);
        //return false if requested was not found
        if (!$requested_record) {
            return Response::error('The requested record does not exist', 404);
        }
        $translations = $model_path::withTrashed()->where('translation_of', $requested_record->id)->get();
        // Unlink files from storage
        File::UnlinkWhenDelete($files_fillable_vales, $requested_record);
        foreach ($translations as $translation) {
            File::UnlinkWhenDelete($files_fillable_vales, $translation);
        }
        //delete translations
        $translations->each->forceDelete();
        //delete default record
        $requested_record->forceDelete();
        if (config('grace.mode') === 'api') {
            return Response::success([
                'Default Record' => $requested_record,
                'Translations' => $translations,
            ], 'The record has been deleted successfully', 200);
        } else if (config('grace.mode') === 'blade') {
            return redirect()->route('grace.' . $table_name . '.index');
        }
    }
}
