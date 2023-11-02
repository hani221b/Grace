<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Hani221b\Grace\Support\Core;
use Hani221b\Grace\Support\Factory;
use Hani221b\Grace\Support\File;
use Hani221b\Grace\Support\Views\Create;
use Hani221b\Grace\Support\Views\Edit;
use Hani221b\Grace\Support\Views\Index;
use Hani221b\Grace\Support\Views\Sidebar;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Hani221b\Grace\Support\Str as GraceStr;

class CreateFullResource extends Controller
{
    protected $file_sys;
    protected $table_name;
    protected $single_table_name;
    protected $controller_namespace;
    protected $model_namespace;
    protected $request_namespace;
    protected $migration_namespace;
    protected $resource_namespace;
    protected $field_names;
    protected $field_types;
    protected $storage_path;
    protected $single_record_table;
    protected $select_options;
    protected $files_fields;
    protected $fillable_files_array;
    protected $input_types;
    protected $singular_class_name;

    public function __construct(Filesystem $file_sys, Request $request)
    {
        $this->file_sys = $file_sys;
        $this->table_name = $request->table_name;
        if ($request->table_name != null) {
            $this->single_table_name = Str::singular($request->table_name);
        }
        $this->controller_namespace = $request->controller_namespace;
        $this->model_namespace = $request->model_namespace;
        $this->request_namespace = $request->request_namespace;
        $this->migration_namespace = $request->migration_namespace;
        $this->resource_namespace = $request->resource_namespace;
        $this->field_names = $request->field_names;
        $this->files_fields = Core::isFileValues($request->field_names, $request->input_types);
        $this->fillable_files_array = Core::filesFillableArray($this->files_fields);
        if ($request->field_types !== null) {
            $this->field_types = array_filter($request->field_types, fn ($value) => !is_null($value) && $value !== '');
        }
        if ($request->input_types !== null) {
            $this->input_types = array_filter($request->input_types, fn ($value) => !is_null($value) && $value !== '');
        }
        $this->storage_path = $request->storage_path;
        $this->single_record_table = $request->single_record_table;
        $this->select_options = $request->select_options;
        $this->singular_class_name = GraceStr::singularClass($this->table_name);
    }

    public function executeFileCreation()
    {
        $this->makeFileAlive($this->getMigrationVariables(), "migration", $this->migration_namespace, $this->table_name);
        $this->makeFileAlive($this->getModelVariables(), "model", $this->model_namespace, "");
        $this->makeFileAlive($this->getControllerVariables(), "controller", $this->controller_namespace, "Controller");
        $this->makeFileAlive($this->getRequestVariables(), "request", $this->request_namespace, "Request");
        $this->makeFileAlive($this->getResourceVariables(), "resource", $this->resource_namespace, "Resource");
        $this->makeRoutes();
        $this->makeDisk();
        if (config('grace.mode') === 'blade') {
            $this->makeViews();
        }
    }

    public function makeFullResourceAlive()
    {
        $new_table_to_be_registered = Table::where('table_name', $this->table_name)->first();
        if ($new_table_to_be_registered !== null) {
            return 'Table already exist';
        } else {
            $this->executeFileCreation();
            Table::create([
                'table_name' => $this->table_name,
                'controller' => "{$this->controller_namespace}/{$this->singular_class_name}Controller",
                'model' => $this->model_namespace . '/' . $this->singular_class_name,
                'request' => $this->request_namespace . '/' . $this->singular_class_name . 'Request',
                'resource' => $this->resource_namespace . '/' . $this->singular_class_name . "Resource",
                'migration' => $this->migration_namespace . '/' . date("Y_m_d") . "_" . $_SERVER['REQUEST_TIME']
                    . "_create_" . GraceStr::pluralLower($this->table_name) . "_table",
                'views' => config('grace.views_folder_name') . '/' . $this->table_name,
            ]);
            Artisan::call("cache:clear");
            return redirect()->route('success');
        }
    }

    public function getMigrationVariables()
    {
        return [
            'table_name' => $this->table_name,
            'namespace' => $this->migration_namespace,
            'field_types' => $this->field_types,
            'field_names' => $this->field_names,
        ];
    }

    public function getModelVariables()
    {
        return [
            'namespace' => GraceStr::namespaceCorrection($this->model_namespace),
            'class_name' => $this->singular_class_name,
            'table_name' => $this->table_name,
            'fillable_array' => Factory::modelFillableArray($this->field_names),
            'storage_path' => $this->storage_path,
            'files_fields' => Core::filesFillableArray($this->files_fields),
        ];
    }

    public function getControllerVariables()
    {
        $singular_class_name = $this->singular_class_name;

        return [
            'namespace' => GraceStr::namespaceCorrection($this->controller_namespace),
            'model_path' =>  GraceStr::namespaceCorrection($this->model_namespace) . "\\" . $this->singular_class_name,
            'resource_path' => GraceStr::namespaceCorrection($this->resource_namespace)  . "\\" . $this->singular_class_name . "Resource",
            'request_namespace' => GraceStr::namespaceCorrection($this->request_namespace)  . "\\" . $this->singular_class_name . "Request",
            'request_class' => $this->singular_class_name . "Request",
            'class_name' => $this->singular_class_name . 'Controller',
            'table_name' => $this->table_name,
            'fillable_array' => Core::fillableArray($this->field_names, $this->files_fields),
            'fillable_files_array' => Core::filesFillableArray($this->files_fields),
        ];
    }

    public function getRequestVariables()
    {
        return [
            'namespace' => GraceStr::namespaceCorrection($this->request_namespace),
            'class_name' => $this->singular_class_name . 'Request',
            'table_name' => $this->table_name,

        ];
    }

    public function getResourceVariables()
    {
        return [
            'namespace' => GraceStr::namespaceCorrection($this->resource_namespace),
            'class_name' => $this->singular_class_name . "Resource",

        ];
    }

    public function getRoutesVariables()
    {
        return [
            'table_name' => $this->table_name,
            'controller_name' => $this->singular_class_name . "Controller",
            'controller_namespace' => GraceStr::namespaceCorrection($this->controller_namespace),
        ];
    }

    public function getDiskVariables()
    {
        return [
            'table_name' => $this->table_name,
            'storage_path' => $this->storage_path,
        ];
    }

    public function getCreateViewVariables()
    {
        return [
            'field_names' => $this->field_names,
            'input_types' => $this->input_types,
            'table_name' => $this->table_name,
            'url' => "{{ route('grace.$this->table_name.store') }}",
            'select_options' => $this->select_options,
        ];
    }

    public function getEditViewVariables()
    {
        return [
            'field_names' => $this->field_names,
            'input_types' => $this->input_types,
            'table_name' => $this->table_name,
            'key' => Str::singular($this->table_name),
            'url' => "{{ route('grace.$this->table_name.update', " . "$$this->single_table_name" . "->id) }}",
            'select_options' => $this->select_options,
        ];
    }

    public function getIndexViewVariables()
    {
        return [
            'field_names' => $this->field_names,
            'input_types' => $this->input_types,
            'table_name' => $this->table_name,
            'title' => Str::ucfirst($this->table_name),
            'key' => Str::singular($this->table_name),
        ];
    }

    public function getSidebarViewVariables()
    {
        return [
            'table_name' => $this->table_name,
            'single_record_table' => $this->single_record_table,
        ];
    }

    public function makeRoutes()
    {
        Factory::appendRoutes($this->getRoutesVariables());
    }

    public function makeDisk()
    {
        Factory::appendDisk($this->getDiskVariables());
    }

    public function makeViews()
    {
        Create::make($this->table_name, $this->getCreateViewVariables());
        Edit::make($this->table_name, $this->getEditViewVariables());
        Index::make($this->table_name, $this->getIndexViewVariables());
        Sidebar::append($this->getSidebarViewVariables());
    }

    public function makeFileAlive(array $StubV_ariables, string $type, string $path, string $suffix): void
    {
        $source_file_path = Factory::getSourceFilePath($type);
        $source_file = Factory::getSourceFile($type);
        $type = Factory::getResourceType($type, $this->single_record_table);
        $path = \call_user_func($source_file_path, $path, $this->table_name, $suffix);
        $contents = \call_user_func($source_file, $StubV_ariables, $type);
        File::makeDirectory($this->file_sys, dirname($path));
        File::put($this->file_sys, $path, $contents);
        Factory::MigrateTable($type, $path);
    }
}
