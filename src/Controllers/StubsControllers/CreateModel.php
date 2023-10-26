<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use Hani221b\Grace\Abstracts\Factory;
use Hani221b\Grace\Support\Core;
use Hani221b\Grace\Support\Factory as SupportFactory;
use Hani221b\Grace\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class CreateModel extends Factory
{
    public function __construct(Filesystem $file_sys, Request $request)
    {
        parent::__construct($file_sys, $request);

        $this->suffix = "";
        $this->source_file_type = "model";
        $this->class_name = Str::getSingularClassName($this->table_name);
        $this->files_fields = Core::isFileValues($request->field_names,$request->field_types);
        $this->fillable_array = SupportFactory::modelFillableArray($this->field_names);
        $this->files_fields = Core::filesFillableArray($this->files_fields);
        $this->namespace = Str::namespaceCorrection($this->namespace);;
        $this->sourceFilePath = "Hani221b\Grace\Support\File::sourceFilePath";
        $this->sourceFile = "Hani221b\Grace\Support\File::modelSourceFile";
    }

    public function getStubVariables()
    {
        return [
            'namespace' => $this->namespace,
            'class_name' => $this->class_name,
            'table_name' => $this->table_name,
            'fillable_array' => $this->fillable_array,
            'files_fields' => $this->files_fields,
        ];
    }

    public function makeAlive()
    {
        return $this->makeFileAlive();
    }
}
