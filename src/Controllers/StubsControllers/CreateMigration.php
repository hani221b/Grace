<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use Hani221b\Grace\Abstracts\Factory;
use Hani221b\Grace\Interfaces\IFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class CreateMigration extends Factory implements IFactory
{
    public function __construct(Filesystem $file_sys, Request $request)
    {
        parent::__construct($file_sys, $request);

        $this->suffix = $this->table_name;
        $this->source_file_type = "migration";
        if ($request->field_types !== null) {
            $this->field_types =
                 array_filter($request->field_types, fn ($value) =>
                     !is_null($value) && $value !== "");
        }
        $this->sourceFilePath = "Hani221b\Grace\Support\File::migrationSourceFilePath";
        $this->sourceFile = "Hani221b\Grace\Support\File::migrationSourceFile";
    }

    public function getStubVariables()
    {
        return [
            "table_name" => $this->table_name,
            "namespace" => $this->namespace,
            "field_types" => $this->field_types,
            "field_names" => $this->field_names,
        ];
    }
    public function makeAlive()
    {
        return $this->makeFileAlive();
    }
}
