<?php

namespace Hani221b\Grace\Controllers\Validations;

use Hani221b\Grace\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ValidationController 
{
    public function getAddValidationRulesOnFields(int $id): View
    {
        $table = Table::where("id", $id)->first();
        $fields = array_diff(Schema::getColumnListing($table->table_name), ["id", "translation_lang", "translation_of", "status", "order", "created_at", "updated_at", "deleted_at"]);
        $fields = array_values($fields);
        return view("Grace::pages.validations", compact("fields", "id"));
    }

    public function submitValidationRulesOnFields(Request $request): RedirectResponse
    {
        $table = Table::where("id", $request->table_id)->select("request", "table_name")->first();
        $request_file = file_get_contents(base_path() . "/" . $table->request . ".php");
        $validations = array_values($request->validation);
        $fields_array = array();
        $rules_array = array();
        $validation_template = "";
        foreach ($validations as $validation) {
            $field = $validation["field"];
            $rules = array_unique($validation["rules"]);
            $options = $validation["options"];
            $rules_and_options = [];
            $combine_rules_with_options = array_combine($rules, $options);
            foreach ($combine_rules_with_options as $rule => $option) {
                if ($option !== null) {
                    array_push($rules_and_options, "$rule:$option");
                } else if ($option === null) {
                    array_push($rules_and_options, "$rule");
                }
            }
            array_push($fields_array, $field);
            array_push($rules_array, implode("|", $rules_and_options));
            $validation_array = array_combine($fields_array, $rules_array);
        }
        foreach ($validation_array as $field => $rules) {
            $validation_template .= "'$table->table_name.*.$field' => '$rules'," . "\n";
        }

        $contents = str_replace("//rules go here [DO NOT REMOVE THIS COMMENT]", $validation_template, $request_file);
        file_put_contents(base_path() . "/" . $table->request . ".php", $contents);
        return redirect()->route("success");
    }
}