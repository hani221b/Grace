<?php

namespace Hani221b\Grace\Controllers;

use Hani221b\Grace\Models\Language;
use Hani221b\Grace\Models\Table;
use Hani221b\Grace\Support\Core;
use Hani221b\Grace\Support\File;
use Hani221b\Grace\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class DashboardController
{
    public function grace_cp(): View
    {
        return view('Grace::pages.main');
    }

    public function get_dashboard(): View
    {
        return view('Grace::Grace.dashboard');
    }

    public function get_languages(): View
    {
        $languages = Language::Selection()->get();
        return view('grace.languages.index', compact('languages'));
    }

    public function success()
    {
        return view('Grace::pages.success');
    }


    public function changeStatusForLanguage(int $id): RedirectResponse
    {
        $language = Language::where('id', $id)->select('id', 'status')->first();
        $status = $language->status == 0 ? 1 : 0;
        $language->update(['status' => $status]);
        return redirect()->back();
    }


    public function setLanguageAsDefault(int $id): RedirectResponse
    {
        $default_language = Language::where('default', 1)->select('id', 'default')->first();
        $default_language->update(['default' => 0]);
        $language = Language::where('id', $id)->select('id', 'default')->first();
        $language->update(['default' => 1]);
        return redirect()->back();
    }

    public function getTables(): View
    {
        $tables = Table::get();
        return view('Grace::pages.tables', compact('tables'));
    }


    public function delete_table(int $id): RedirectResponse
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

        //remove route controller use statement
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
        if(file_exists($sidebar_file)){
            $sidebar_file_content = file_get_contents($sidebar_file);
            $item = Str::getBetween($sidebar_file_content, $sidebar_item_start, $sidebar_item_end);
            $full_item = $sidebar_item_start . $item . $sidebar_item_end;
            $new_sidebar_file = str_replace($full_item, '', $sidebar_file_content);
            file_put_contents($sidebar_file, $new_sidebar_file);
        }

        //remove views
        File::deleteDir(base_path() . '/resources/views/' . config('grace.views_folder_name') . '/' . $table->table_name);

        //remove table
        Schema::dropIfExists($table->table_name);

        //delete from table's table
        $table->delete();

        Artisan::call("cache:clear");
        return redirect()->route('success');
    }





    public function getAddRelation(int $id): View
    {
        $table = Table::where('id', $id)->first();
        $db_fields = [];
        $db_tables = Core::getAllTables();
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
