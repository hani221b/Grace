<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use App\Http\Controllers\Controller;
use Hani221b\Grace\Support\File;
use Illuminate\Http\Request;

class CreateDisk extends Controller
{
    /**
     * Class properties
     * @return string
     */
    protected $table_name;
    protected $namespace;
    protected $class_name;
    protected $files;

    /**
     * Create a new command instance.
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->table_name = $request->table_name;
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
            'table_name' => $this->table_name,
        ];
    }

    /**
     * Execute the file creation.
     */
    public function makeDiskAlive()
    {
        $path = File::sourceFilePath($this->namespace, $this->class_name, '');

        File::makeDirectory($this->files, dirname($path));

        $contents = File::sourceFile($this->getStubVariables(), 'model');

        File::put($this->files, $path, $contents);

        return redirect()->route('success');
    }
}
