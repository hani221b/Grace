<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use App\Http\Controllers\Controller;
use Hani221b\Grace\Support\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class CreateMigration extends Controller
{
    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;
    protected $table_name;
    protected $namespace;
    protected $field_names;
    protected $field_types;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files, Request $request)
    {
        $this->files = $files;
        $this->table_name = $request->table_name;
        $this->namespace = $request->namespace;
        $this->field_names = $request->field_names;
        //filtering null values
        if ($request->field_types !== null) {
            $this->field_types = array_filter($request->field_types, fn ($value) => !is_null($value) && $value !== '');
        }
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
            'namespace' => $this->namespace,
            'field_types' => $this->field_types,
            'field_names' => $this->field_names,
        ];
    }

    /**
     * Execute the file creation.
     */
    public function makeMigrationAlive()
    {
        $path = File::migrationSourceFilePath($this->namespace, $this->table_name);

        File::makeDirectory($this->files, dirname($path));

        $contents = File::migrationSourceFile($this->getStubVariables(), 'migration');

        File::put($this->files, $path, $contents);

        return redirect()->route('success');
    }
}
