<?php

namespace Hani221b\Grace\Abstracts;
use Hani221b\Grace\Interfaces\IFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

 abstract class Factory implements IFactory  {
    protected $file_sys;
    protected $path;
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
    protected $field_types;
    protected $fillable_array;
    protected $request_path;


    public function __construct(Filesystem $file_sys, Request $request) {
        $this->file_sys = $file_sys;
        $this->path = $request->namespace;
        $this->namespace = $request->namespace;
        $this->table_name = $request->table_name;
        $this->class_name = $request->class_name;
        $this->model_path = $request->model_path;
        $this->resource_path = $request->resource_path;
        $this->request_namespace = $request->request_namespace;
        $this->request_class = $request->request_class;
        $this->model_namespace = $request->model_namespace;
        $this->resource_namespace = $request->resource_namespace;
        $this->fillable_array = $request->fillable_array;
        $this->fillable_files_array = $request->fillable_files_array;
        $this->files_fields = $request->files_fields;
        $this->field_names = $request->field_names;
        $this->field_types = $request->field_types;
        $this->request_path = $request->request_path;

    }

    // public function getStubVariables()
    // {
    //     return [
    //         'namespace' => Str::namespaceCorrection($this->namespace),
    //         'class_name' => Str::singularClass($this->table_name) . 'Controller',
    //         'table_name' => $this->table_name,
    //         'model_path' => $this->model_path . "/" . Str::singularClass($this->table_name),
    //         'resource_path' => $this->resource_path . "/" . Str::singularClass($this->table_name) . 'Resource',
    //         'fillable_array' => Core::fillableArray($this->field_names, $this->files_fields),
    //         'fillable_files_array' => "'" . str_replace(",", "', '", $this->fillable_files_array) . "'",
    //         'request_path' => Str::namespaceCorrection($this->request_namespace) . "\\" . Str::singularClass($this->table_name) . 'Request',
    //         'request_class' => 'Request',
    //         'field_types' => $this->field_types,
    //         'field_names' => $this->field_names,
    //     ];
    // }
    public function getStubVariables()
    {
        $vars =  [
            'namespace' => $this->namespace,
            'class_name' => $this->class_name,
            'table_name' => $this->table_name,
            'model_path' => $this->model_path,
            'resource_path' => $this->resource_path,
            'fillable_array' => $this->fillable_array,
            'fillable_files_array' => $this->fillable_files_array,
            'request_path' => $this->request_path,
            'request_class' => $this->request_class,
            'field_types' => $this->field_types,
            'field_names' => $this->field_names,
            'model_namespace' => $this->model_namespace,
            'request_namespace' => $this->request_namespace,
        ];

        return array_filter($vars, fn($val) => $val !== '' && !is_array($val));
    }
}
