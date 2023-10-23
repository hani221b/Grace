<?php

namespace Hani221b\Grace\Controllers\StubsControllers;

use Hani221b\Grace\Interfaces\IFactory;
use Hani221b\Grace\Support\File;
use Hani221b\Grace\Abstracts\Factory;

class CreateController extends Factory implements IFactory
{
    public function makeControllerAlive()
    {
        $controller_path = File::sourceFilePath($this->namespace, $this->table_name, 'Controller');
        File::makeDirectory($this->fs, dirname($controller_path));
        $controller_contents = File::sourceFile($this->getStubVariables(), 'controller');
        File::put($this->fs, $controller_path, $controller_contents);
        return redirect()->route('success');
    }
}
