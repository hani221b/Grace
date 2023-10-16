<?php

namespace Hani221b\Grace\Operations;

use Hani221b\Grace\Support\Response;

class ChangeStatusForMultiLanguageRecord
{
    /**
     * this function change the status of multi language record.
     *
     * @param int $id
     * @param string $model_path
     * @return \Illuminate\Http\Response
     */

    public static function changeStatus($id, $model_path, $table_name)
    {
        //fetch the requested record
        $requested_record = $model_path::find($id);
        //return false if requested was not found
        if (!$requested_record) {
            return Response::error('The requested record does not exist', 404);
        }
        //switch the status value of the requested record
        $status = $requested_record->status == 0 ? 1 : 0;
        //update the status with the new value
        $requested_record->update(['status' => $status]);
        //get translations records
        $translations = $requested_record->translations;
        //update the status of translations
        $translations->each->update(['status' => $status]);
        $all_records = $model_path::where('id', $id)->with('translations')->get();
        if (config('grace.mode') === 'api') {
            return Response::success($all_records, 'The status of the record has been changed successfully', 200);
        } else if (config('grace.mode') === 'blade') {
            return redirect()->route("grace.$table_name.index");
        }
    }
}
