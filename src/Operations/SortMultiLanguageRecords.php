<?php

namespace Hani221b\Grace\Operations;

use Hani221b\Grace\Support\Response;

class SortMultiLanguageRecords
{

    /**
     * this function change the sort of multi language records.
     *
     * @param int $id
     * @param string $model_path
     * @return \Illuminate\Http\JsonResponse
     */

    public static function sort($records_from_request, $model_path, $table_name)
    {
        //fetch all default language records
        $default_languages_records = $model_path::DefaultLanguage()->select('id', 'order')->get();

        foreach ($default_languages_records as $record) {
            $record_id = $record->id;
            foreach ($records_from_request as $new_ordered_record) {
                if ($new_ordered_record['id'] == $record_id) {
                    $record->update(['order' => $new_ordered_record['order']]);
                }
            }
        }
        if (config('grace.mode') === 'api') {
            return Response::success(null, 'The status of the record has been changed successfully', 200);
        } else if (config('grace.mode') === 'blade') {
            return redirect('/' . $table_name);
        }
    }
}
