<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use Hani221b\Grace\Interfaces\IFactory;
use Hani221b\Grace\Support\File;
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

        $this->namespace = Str::namespaceCorrection($this->namespace);
        $this->class_name = Str::singularClass($this->table_name) . 'Controller';
        $this->model_namespace = $this->model_namespace . "/" . Str::singularClass($this->table_name);
        $this->files_fields = Core::isFileValues($request->field_names, $request->field_types);
        $this->fillable_files_array = Core::filesFillableArray($this->files_fields);
        $this->request_class =  "Request";
        $this->fillable_array = Core::fillableArray($this->field_names, $this->files_fields);
        $this->request_namespace = Str::namespaceCorrection($this->request_namespace);
    }
    public function makeAlive()
    {
        $path = File::sourceFilePath($this->path, $this->table_name, 'Controller');
        File::makeDirectory($this->file_sys, dirname($path));
        $controller_contents = File::sourceFile($this->getStubVariables(), 'controller');
        File::put($this->file_sys, $path, $controller_contents);
        return redirect()->route('success');
    }
}
