<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use Hani221b\Grace\Abstracts\Factory;
use Hani221b\Grace\Interfaces\IFactory;
use Hani221b\Grace\Support\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class CreateMigration extends Factory implements IFactory
{

    public function __construct(Filesystem $file_sys, Request $request)
    {
        parent::__construct($file_sys, $request);

        if ($request->field_types !== null) {
            $this->field_types =
                 array_filter($request->field_types, fn ($value) =>
                     !is_null($value) && $value !== '');
        }
    }
    public function makeAlive()
    {
        $path = File::migrationSourceFilePath($this->namespace, $this->table_name);
        File::makeDirectory($this->file_sys, dirname($path));
        $contents = File::migrationSourceFile($this->getStubVariables(), 'migration');
        File::put($this->file_sys, $path, $contents);
        return redirect()->route('success');
    }
}
