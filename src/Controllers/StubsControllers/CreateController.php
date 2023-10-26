<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use Hani221b\Grace\Interfaces\IFactory;
use Hani221b\Grace\Abstracts\Factory;
use Hani221b\Grace\Support\Core;
use Hani221b\Grace\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class CreateController extends Factory implements IFactory
{
    public function __construct(Filesystem $file_sys, Request $request)
    {
        parent::__construct($file_sys, $request);

        $this->suffix = "Controller";
        $this->source_file_type = "controller";
        $this->namespace = Str::namespaceCorrection($this->namespace);
        $this->class_name = Str::singularClass($this->table_name) . $this->suffix;
        $this->model_namespace = $this->model_namespace . "/" . Str::singularClass($this->table_name);
        $this->files_fields = Core::isFileValues($request->field_names, $request->field_types);
        $this->fillable_files_array = Core::filesFillableArray($this->files_fields);
        $this->request_class =  "Request";
        $this->fillable_array = Core::fillableArray($this->field_names, $this->files_fields);
        $this->request_namespace = Str::namespaceCorrection($this->request_namespace);
        $this->resource_path = $this->resource_path . "/" . Str::singularClass($this->table_name) . 'Resource';

        $this->sourceFilePath = "Hani221b\Grace\Support\File::sourceFilePath";
        $this->sourceFile = "Hani221b\Grace\Support\File::sourceFile";
    }

    public function getStubVariables(){
        return [
            'namespace' => $this->namespace,
            'class_name' => $this->class_name,
            'table_name' => $this->table_name,
            'model_path' => $this->model_namespace,
            'request_namespace' => $this->request_namespace,
            'resource_path' => $this->resource_path,
            'fillable_array' => $this->fillable_array,
            'fillable_files_array' => $this->fillable_files_array,
            'request_class' => $this->request_class,
        ];
    }
    public function makeAlive()
    {
       return $this->makeFileAlive();
    }
}
