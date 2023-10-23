<?php

namespace Hani221b\Grace\Abstracts;
use App\Http\Controllers\Controller;
use Hani221b\Grace\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Hani221b\Grace\Support\Core;

 abstract class Factory  {
    protected $fs;
    protected $namespace;
    protected $table_name;
    protected $class_name;
    protected $model_namespace;
    protected $resource_namespace;
    protected $files_fields;
    protected $field_names;
    protected $fillable_files_array;
    protected $model_path;
    protected $resource_path;
    protected $request_namespace;
    protected $request_class;

    public function __construct(Filesystem $fs, Request $request) {
        $this->fs = $fs;
        $this->namespace = $request->namespace;
        $this->table_name = $request->table_name;
        $this->class_name = $request->class_name;
        $this->model_path = $request->model_path;
        $this->resource_path = $request->resource_path;
        $this->request_namespace = $request->request_namespace;
        $this->request_class = $request->request_class;
        $this->model_namespace = $request->model_namespace;
        $this->resource_namespace = $request->resource_namespace;
        $this->files_fields = Core::isFileValues($request->field_names, $request->field_types);
        $this->field_names = $request->field_names;
        $this->fillable_files_array = Core::filesFillableArray($this->files_fields);

    }

    public function getStubVariables()
    {
        return [
            'namespace' => Str::namespaceCorrection($this->namespace),
            'class_name' => Str::singularClass($this->table_name) . 'Controller',
            'table_name' => $this->table_name,
            'model_path' => $this->model_path . "/" . Str::singularClass($this->table_name),
            'resource_path' => $this->resource_path . "/" . Str::singularClass($this->table_name) . 'Resource',
            'fillable_array' => Core::fillableArray($this->field_names, $this->files_fields),
            'fillable_files_array' => "'" . str_replace(",", "', '", $this->fillable_files_array) . "'",
            'request_path' => Str::namespaceCorrection($this->request_namespace) . "\\" . Str::singularClass($this->table_name) . 'Request',
            'request_class' => 'Request',
        ];
    }
}
