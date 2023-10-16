<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use Hani221b\Grace\Support\File;
use Illuminate\Filesystem\Filesystem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hani221b\Grace\Support\Str as GraceStr;

class CreateResource extends Controller
{
    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;
    protected $namespace;
    protected $class_name;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files, Request $request)
    {
        $this->files = $files;
        $this->namespace = $request->namespace;
        $this->class_name = $request->class_name;
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
            'namespace' => $this->namespace,
            'class_name' => GraceStr::singularClass($this->class_name),
        ];
    }

    /**
     * Execute the console command.
     */
    public function makeResourceAlive()
    {
        $path = File::sourceFilePath($this->namespace, $this->class_name, 'Resource');

        File::makeDirectory($this->files, dirname($path));

        $contents = File::sourceFile($this->getStubVariables(), 'resource');

        File::put($this->files, $path, $contents);

        return redirect()->route('success');
    }
}
