<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Exception;
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
    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;
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

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files, Request $request)
    {
        $this->files = $files;
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
        //filtering null values
        if ($request->field_types !== null) {
            $this->field_types = array_filter($request->field_types, fn ($value) => !is_null($value) && $value !== '');
        }
        //filtering null values
        if ($request->input_types !== null) {
            $this->input_types = array_filter($request->input_types, fn ($value) => !is_null($value) && $value !== '');
        }
        $this->storage_path = $request->storage_path;
        $this->single_record_table = $request->single_record_table;
        $this->select_options = $request->select_options;
    }

    /**
     * Execute the file creation.
     */

    public function executeFileCreation()
    {
        // migration
        $this->makeMigration();
        //model
        $this->makeModel();
        // controller
        $this->makeController();
        //request
        $this->makeRequest();
        //resource
        $this->makeResource();
        //routes
        $this->makeRoutes();
        //disk
        $this->makeDisk();
        //views
        if (config('grace.mode') === 'blade') {
            $this->makeViews();
        }
    }

    /**
     * Execute the file creation.
     */

    public function makeFullResourceAlive()
    {
        $new_table_to_be_registered = Table::where('table_name', $this->table_name)->first();
        if ($new_table_to_be_registered !== null) {
            return 'Table already exist';
        } else {
            $this->executeFileCreation();

            Table::create([
                'table_name' => $this->table_name,
                'controller' => $this->controller_namespace . '/' . GraceStr::singularClass($this->table_name) . 'Controller',
                'model' => $this->model_namespace . '/' . GraceStr::singularClass($this->table_name),
                'request' => $this->request_namespace . '/' . GraceStr::singularClass($this->table_name) . 'Request',
                'resource' => $this->resource_namespace . '/' . GraceStr::singularClass($this->table_name) . "Resource",
                'migration' => $this->migration_namespace . '/' . date("Y_m_d") . "_" . $_SERVER['REQUEST_TIME']
                    . "_create_" . GraceStr::pluralLower($this->table_name) . "_table",
                'views' => config('grace.views_folder_name') . '/' . $this->table_name,
            ]);

            Artisan::call("cache:clear");

            return redirect()->route('success');
        }
    }

    /**
     * Mapping the value of migration stubs variables
     * @return array
     */
    public function getMigrationVariables()
    {
        return [
            'table_name' => $this->table_name,
            'namespace' => $this->migration_namespace,
            'field_types' => $this->field_types,
            'field_names' => $this->field_names,
        ];
    }

    /**
     * Mapping the value of model stubs variables
     * @return array
     */
    public function getModelVariables()
    {
        return [
            'namespace' => GraceStr::namespaceCorrection($this->model_namespace),
            'class_name' => GraceStr::singularClass($this->table_name),
            'table_name' => $this->table_name,
            'fillable_array' => Factory::modelFillableArray($this->field_names),
            'storage_path' => $this->storage_path,
            'files_fields' => Core::filesFillableArray($this->files_fields),
        ];
    }

    /**
     * Mapping the value of controller stubs variables
     * @return array
     */
    public function getControllerVariables()
    {
        return [
            'namespace' => GraceStr::namespaceCorrection($this->controller_namespace),
            'model_path' =>  GraceStr::namespaceCorrection($this->model_namespace) . "\\" . GraceStr::singularClass($this->table_name),
            'resource_path' => GraceStr::namespaceCorrection($this->resource_namespace)  . "\\" . GraceStr::singularClass($this->table_name) . "Resource",
            'request_path' => GraceStr::namespaceCorrection($this->request_namespace)  . "\\" . GraceStr::singularClass($this->table_name) . "Request",
            'request_class' => GraceStr::singularClass($this->table_name) . "Request",
            'class_name' => GraceStr::singularClass($this->table_name) . 'Controller',
            'table_name' => $this->table_name,
            'fillable_array' => Core::fillableArray($this->field_names, $this->files_fields),
            'fillable_files_array' => Core::filesFillableArray($this->files_fields),
        ];
    }

    /**
     * Mapping the value of request stubs variables
     * @return array
     */
    public function getRequestVariables()
    {
        return [
            'namespace' => GraceStr::namespaceCorrection($this->request_namespace),
            'class_name' => GraceStr::singularClass($this->table_name) . 'Request',
            'table_name' => $this->table_name,

        ];
    }

    /**
     * Mapping the value of resource stubs variables
     * @return array
     */

    public function getResourceVariables()
    {
        return [
            'namespace' => GraceStr::namespaceCorrection($this->resource_namespace),
            'class_name' => GraceStr::singularClass($this->table_name) . "Resource",

        ];
    }

    /**
     * Mapping the value of routes stubs variables
     * @return array
     */
    public function getRoutesVariables()
    {
        return [
            'table_name' => $this->table_name,
            'controller_name' => GraceStr::singularClass($this->table_name) . "Controller",
            'controller_namespace' => GraceStr::namespaceCorrection($this->controller_namespace),
        ];
    }

    /**
     * Mapping the value of disk stubs variables
     * @return array
     */
    public function getDiskVariables()
    {
        return [
            'table_name' => $this->table_name,
            'storage_path' => $this->storage_path,
        ];
    }

    /**
     * Mapping the value of create view stubs variables
     * @return array
     */
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

    /**
     * Mapping the value of edit view stubs variables
     * @return array
     */
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

    /**
     * Mapping the value of create index stubs variables
     * @return array
     */
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

    /**
     * Mapping the value of create side stubs variables
     * @return array
     */
    public function getSidebarViewVariables()
    {
        return [
            'table_name' => $this->table_name,
            'single_record_table' => $this->single_record_table,
        ];
    }

    /**
     * Create Migration
     * @return void
     */

    public function makeMigration()
    {
        $path = File::migrationSourceFilePath($this->migration_namespace, $this->table_name);

        File::makeDirectory($this->files, dirname($path));

        $contents = File::migrationSourceFile($this->getMigrationVariables(), 'migration');

        File::put($this->files, $path, $contents);

        if (config('grace.auto_migrate') === true) {
            $base_path = base_path();

            $file_name = str_replace($base_path, '', $path);

            Artisan::call('migrate', ['--path' => $file_name]);
        }
    }

    /**
     * Create Model
     * @return void
     */

    public function makeModel()
    {
        $model_path = File::sourceFilePath($this->model_namespace, $this->table_name, '');

        File::makeDirectory($this->files, dirname($model_path));

        $model_contents = File::modelSourceFile($this->getModelVariables(), 'model');

        File::put($this->files, $model_path, $model_contents);
    }

    /**
     * Create Controller
     * @return void
     */

    public function makeController()
    {
        $controller_path = File::sourceFilePath($this->controller_namespace, $this->table_name, 'Controller');

        File::makeDirectory($this->files, dirname($controller_path));

        if ($this->single_record_table === null) {
            $type = 'controller';
        } else if ($this->single_record_table === "1") {
            $type = 'controller.single.record';
        }
        $controller_contents = File::sourceFile($this->getControllerVariables(), $type);

        File::put($this->files, $controller_path, $controller_contents);
    }

    /**
     * Create Request
     * @return void
     */

    public function makeRequest()
    {
        $request_path = File::sourceFilePath($this->request_namespace, $this->table_name, 'Request');

        File::makeDirectory($this->files, dirname($request_path));

        $request_contents = File::sourceFile($this->getRequestVariables(), 'request');

        File::put($this->files, $request_path, $request_contents);
    }

    /**
     * Create Resource
     * @return void
     */

    public function makeResource()
    {
        $resource_path = File::sourceFilePath($this->resource_namespace, $this->table_name, 'Resource');

        File::makeDirectory($this->files, dirname($resource_path));

        $resource_contents = File::sourceFile($this->getResourceVariables(), 'resource');

        File::put($this->files, $resource_path, $resource_contents);
    }

    /**
     * Create Routes
     * @return void
     */

    public function makeRoutes()
    {
        Factory::appendRoutes($this->getRoutesVariables());
    }

    /**
     * Create Disk
     * @return void
     */

    public function makeDisk()
    {
        Factory::appendDisk($this->getDiskVariables());
    }

    /**
     * Create Disk
     * @return void
     */

    public function makeViews()
    {
        Create::make($this->table_name, $this->getCreateViewVariables());
        Edit::make($this->table_name, $this->getEditViewVariables());
        Index::make($this->table_name, $this->getIndexViewVariables());
        Sidebar::append($this->getSidebarViewVariables());
    }
}
