<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use Illuminate\Filesystem\Filesystem;
use Hani221b\Grace\Abstracts\Factory;
use Illuminate\Http\Request;
use Hani221b\Grace\Support\Str;
class CreateRequest extends Factory
{

    public function __construct(Filesystem $file_sys, Request $request)
    {
        parent::__construct($file_sys, $request);

        $this->suffix = "Request";
        $this->source_file_type = "request";
        $this->sourceFilePath = "Hani221b\Grace\Support\File::sourceFilePath";
        $this->sourceFile = "Hani221b\Grace\Support\File::sourceFile";
        $this->class_name = Str::singularClass($this->table_name) . $this->suffix;
        $this->namespace = Str::namespaceCorrection($this->namespace);;

    }

    public function getStubVariables()
    {
        return [
            'namespace' => $this->namespace,
            'class_name' => $this->class_name,
            'table_name' => $this->table_name,
        ];
    }

    public function makeAlive()
    {
        return $this->makeFileAlive();
    }
}
