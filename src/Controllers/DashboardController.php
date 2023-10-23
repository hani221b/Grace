<?php

namespace Hani221b\Grace\Controllers;

use App\Models\Language;
use App\Models\Table;
use Exception;
use Hani221b\Grace\Support\File;
use Hani221b\Grace\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController
{
    public function grace_cp()
    {
        return view('Grace::pages.main');
    }

    /**
     * get user dashboard
     */

    public function get_dashboard()
    {
        return view('Grace::Grace.dashboard');
    }

    /**
     * get all languages
     */

    public function get_languages()
    {
        try {
            $languages = Language::Selection()->get();
            return view('grace.languages.index', compact('languages'));
        } catch (Exception $exception) {
            return 'something went wrong. please try again later';
        }
    }
    /**
     * return success page
     */
    public function success(){
        return view('Grace::pages.success');
    }

    /**
     * make a language active of inactive
     */

    public function change_status_for_language($id)
    {
        try {
            $language = Language::where('id', $id)->select('id', 'status')->first();
            $status = $language->status == 0 ? 1 : 0;
            //update the status with the new value
            $language->update(['status' => $status]);
            return \redirect()->back();
        } catch (Exception $exception) {
            return 'something went wrong. please try again later';
        }
    }

    /**
     * override system config and change default language
     */

    public function set_language_to_default($id)
    {
        try {
            // setting default language back to non default
            $default_language = Language::where('default', 1)->select('id', 'default')->first();
            $default_language->update(['default' => 0]);
            // setting new default language
            $language = Language::where('id', $id)->select('id', 'default')->first();
            $language->update(['default' => 1]);
            return \redirect()->back();
        } catch (Exception $exception) {
            return 'something went wrong. please try again later';
        }
    }

    /**
     * get all tables
     */

    public function get_tables()
    {
        try {
            $tables = Table::get();
            return view('Grace::pages.tables', compact('tables'));
        } catch (Exception $exception) {
            return 'something went wrong. please try again later';
        }
    }

    /**
     * delete table with all its files and classes
     */
    public function delete_table($id)
    {
        $table = Table::where('id', $id)->first();
        $resources = [
            base_path() . '/' . $table->controller . '.php',
            base_path() . '/' . $table->model . '.php',
            base_path() . '/' . $table->request . '.php',
            base_path() . '/' . $table->resource . '.php',
            base_path() . '/' . $table->resource . '.php',
            base_path() . '/' . $table->migration . '.php',
            base_path() . '/' . $table->views . '.php'
        ];
        foreach($resources as $resource){
            if (file_exists($resource)) {
                unlink($resource);
            }
        }

        // removing route
        $route_start = "/*<$table->table_name-routes>*/";
        $route_end = "/*</$table->table_name-routes>*/";
        $route_file_name = base_path() . '/routes/grace.php';
        $route_file = file_get_contents($route_file_name);
        $route = Str::getBetween($route_file, $route_start, $route_end);
        $full_route = $route_start . $route . $route_end;
        $new_route_file = str_replace($full_route, '', $route_file);
        file_put_contents($route_file_name, $new_route_file);

        //remove route controlle use statement

        $use_statement_start = "/*<$table->table_name-controller>*/";
        $use_statement_end = "/*</$table->table_name-controller>*/";
        $use_statement = Str::getBetween($route_file, $use_statement_start, $use_statement_end);
        $full_use_statement = $use_statement_start . $use_statement . $use_statement_end;
        $new_route_file = str_replace($full_use_statement, '', $new_route_file);
        file_put_contents($route_file_name, $new_route_file);

        //remove disk

        $disk_start = "/*<$table->table_name-disk>*/";
        $disk_end = "/*</$table->table_name-disk>*/";
        $file_system = base_path() . '/config/filesystems.php';
        $file_system_content = file_get_contents($file_system);
        $disk = Str::getBetween($file_system_content, $disk_start, $disk_end);
        $full_disk = $disk_start . $disk . $disk_end;
        $new_file_system = str_replace($full_disk, '', $file_system_content);
        file_put_contents($file_system, $new_file_system);

        //remove sidebar list item

        $sidebar_item_start = "<!--<$table->table_name>-->";
        $sidebar_item_end = "<!--</$table->table_name>-->";
        $sidebar_file = base_path() . '/resources/views/grace/includes/sidebar.blade.php';
        $sidebar_file_content = file_get_contents($sidebar_file);
        $item = Str::getBetween($sidebar_file_content, $sidebar_item_start, $sidebar_item_end);
        $full_item = $sidebar_item_start . $item . $sidebar_item_end;
        $new_sidebar_file = str_replace($full_item, '', $sidebar_file_content);
        file_put_contents($sidebar_file, $new_sidebar_file);

        //remove views

        File::deleteDir(base_path() . '/resources/views/' . config('grace.views_folder_name') . '/' . $table->table_name);

        //remove table

        Schema::dropIfExists($table->table_name);

        //delete from table's table

        $table->delete();

        return redirect()->route('success');
    }

    /**
     * Adding validation rules on the fields of specific table
     */
    public function add_validation($id)
    {
        $table = Table::where('id', $id)->first();
        $fields = array_diff(Schema::getColumnListing($table->table_name), ['id', 'translation_lang', 'translation_of', 'status', 'order', 'created_at', 'updated_at', 'deleted_at']);
        $fields = array_values($fields);
        return view('Grace::pages.validations', compact('fields', 'id'));
    }

    /**
     * Adding validation rule on the fields of specific table
     */
    public function submit_validation(Request $request)
    {
        $table = Table::where('id', $request->table_id)->select('request', 'table_name')->first();
        $request_file = file_get_contents(base_path() . '/' . $table->request . '.php');
        $validations = array_values($request->validation);
        $fields_array = array();
        $rules_array = array();
        $validation_template = '';
        foreach ($validations as $validation) {
            $field = $validation['field'];
            $rules = array_unique($validation['rules']);
            $options = $validation['options'];
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
            array_push($rules_array, implode('|', $rules_and_options));
            $validation_array = array_combine($fields_array, $rules_array);
        }
        foreach ($validation_array as $field => $rules) {
            $validation_template .= "'$table->table_name.*.$field' => '$rules'," . "\n";
        }

        $contents = str_replace('//rules go here [DO NOT REMOVE THIS COMMENT]', $validation_template, $request_file);
        file_put_contents(base_path() . '/' . $table->request . '.php', $contents);
        return redirect()->route('success');
    }

    /**
     * retuns the view for adding relations for for models
     */
    public function add_relation($id)
    {
        $table = Table::where('id', $id)->first();
        $db_tables = [];
        $db_fields = [];
        $property = 'Tables_in_' . config('database.connections.mysql.database');
        foreach (DB::select('SHOW TABLES') as $db_table) {
            array_push($db_tables, $db_table->$property);
        }

        $db_tables = array_diff($db_tables, ['failed_jobs', 'languages', 'migrations', 'password_resets', 'personal_access_tokens', 'tables', $table->table_name]);
        $db_tables = array_values($db_tables);
        foreach ($db_tables as $db_table) {
            $fields = array_diff(Schema::getColumnListing($db_table), ['translation_lang', 'translation_of', 'status', 'order', 'created_at', 'updated_at', 'deleted_at', 'email_verified_at', 'password', 'remember_token']);
            array_push($db_fields, $fields);
        }
        $db_fields = array_combine($db_tables, $db_fields);
        $fields = array_diff(Schema::getColumnListing($table->table_name), ['translation_lang', 'translation_of', 'status', 'order', 'created_at', 'updated_at', 'deleted_at']);
        $fields = array_values($fields);
        return view('Grace::pages.relations', compact('fields', 'table', 'db_tables', 'db_fields'));
    }
}
