<?php

namespace Hani221b\Grace\Abstracts;
use Hani221b\Grace\Support\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

 abstract class Factory {
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
    protected $suffix;
    protected $source_file_type;

    protected $sourceFilePath;
    protected $sourceFile;


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
        $this->suffix = $request->suffix;
        $this->source_file_type = $request->source_file_type;


    }

    abstract public function getStubVariables();

    public function makeFileAlive()
    {
        $path = \call_user_func($this->sourceFilePath, $this->path, $this->table_name, $this->suffix);
        $contents = \call_user_func($this->sourceFile, $this->getStubVariables(), $this->source_file_type);
        File::makeDirectory($this->file_sys, dirname($path));
        File::put($this->file_sys, $path, $contents);
        return redirect()->route("success");
    }
}
