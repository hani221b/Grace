<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use App\Http\Controllers\Controller;
use Hani221b\Grace\Support\Core;
use Hani221b\Grace\Support\File;
use Hani221b\Grace\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;
    protected $class_name;
    protected $namespace;
    protected $table_name;
    protected $model_path;
    protected $resource_path;
    protected $field_names;
    protected $field_types;
    protected $files_fields;
    protected $fillable_files_array;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files, Request $request)
    {
        $this->files = $files;
        $this->class_name = $request->class_name;
        $this->namespace = $request->namespace;
        $this->table_name = $request->table_name;
        $this->model_path = $request->model_namespace;
        $this->resource_path = $request->resource_namespace;
        $this->files_fields = Core::isFileValues($request->field_names, $request->field_types);
        $this->field_names = $request->field_names;
        $this->fillable_files_array = Core::filesFillableArray($this->files_fields);
    }

    /**
     **
     * Map the stub variables present in stub to its value
     *
     * @return array
     *
     */
    public function getStubVariables()
    {
        return [
            'namespace' => $this->namespace,
            'class_name' => Str::singularClass($this->table_name) . 'Controller',
            'table_name' => $this->table_name,
            'model_path' => $this->model_path . "/" . Str::singularClass($this->table_name),
            'resource_path' => $this->resource_path . "/" . Str::singularClass($this->table_name) . 'Resource',
            'fillable_array' => Core::fillableArray($this->field_names, $this->files_fields),
            'fillable_files_array' => "'" . str_replace(",", "', '", $this->fillable_files_array) . "'",
        ];
    }

    /**
     * Execute the file creation.
     */
    public function makeControllerAlive()
    {
        $controller_path = File::sourceFilePath($this->namespace, $this->table_name, 'Controller');

        File::makeDirectory($this->files, dirname($controller_path));

        $controller_contents = File::sourceFile($this->getStubVariables(), 'controller');

        File::put($this->files, $controller_path, $controller_contents);

        return redirect()->route('success');
    }
}
