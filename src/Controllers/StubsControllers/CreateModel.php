<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use Hani221b\Grace\Abstracts\Factory as AbstractsFactory;
use Hani221b\Grace\Support\Core;
use Hani221b\Grace\Support\Factory;
use Hani221b\Grace\Support\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Pluralizer;

class CreateModel extends AbstractsFactory
{
    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;
    protected $namespace;
    protected $class_name;
    protected $table_name;
    protected $field_names;
    protected $field_types;
    protected $files_fields;
    protected $storage_path;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files, Request $request)
    {
        $this->files = $files;
        $this->namespace = $request->namespace;
        $this->table_name = $request->table_name;
        $this->class_name = $this->getSingularClassName();
        $this->field_names = $request->field_names;
        $this->files_fields = $request->files_fields;
        $this->files_fields = Core::isFileValues($request->field_names,$request->field_types);
    }

    /**
     * Return the Singular Capitalize Name
     * @param $name
     * @return string
     */
    public function getSingularClassName()
    {
        return $this->table_name !== null ? ucwords(Pluralizer::singular($this->table_name)) : "";
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
            'class_name' => $this->class_name,
            'table_name' => $this->table_name,
            'fillable_array' => Factory::modelFillableArray($this->field_names),
            'files_fields' => Core::filesFillableArray($this->files_fields),
        ];
    }

    /**
     * Execute the file creation.
     */
    public function makeAlive()
    {
        $model_path = File::sourceFilePath($this->namespace, $this->table_name, '');
        File::makeDirectory($this->files, dirname($model_path));
        $model_contents = File::modelSourceFile($this->getStubVariables(), 'model');
        File::put($this->files, $model_path, $model_contents);
        return redirect()->route('success');
    }
}
