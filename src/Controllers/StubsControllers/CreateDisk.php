<?php

// namespace Hani221b\Grace\Controllers\StubsControllers;

// use Hani221b\Grace\Abstracts\Factory;
// use Hani221b\Grace\Interfaces\IFactory;
// use Hani221b\Grace\Support\File;

// class CreateDisk extends Factory implements IFactory
// {
//     public function makeAlive()
//     {
//         $path = File::sourceFilePath($this->namespace, $this->class_name, '');
//         File::makeDirectory($this->file_sys, dirname($path));
//         $contents = File::sourceFile($this->getStubVariables(), 'model');
//         File::put($this->file_sys, $path, $contents);
//         return redirect()->route('success');
//     }
// }
