<?php

namespace Hani221b\Grace\Helpers;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class ResourceRegistrar extends OriginalRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'recycle', 'restore', 'destroy', 'change_status', 'get_sort', 'sort'];

    /**
     * Add certain method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @param  string   $method
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceRoute($name, $base, $controller, $options, $method, $params, $type)
    {
        $uri = $this->getResourceUri($name) . '/' . $method . $params;

        $action = $this->getResourceAction($name, $controller, $method, $options);

        return $this->router->$type($uri, $action);
    }

    /**
     * Adding recycle method for a resourcful route
     *
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @param  string   $method
     * @return \Illuminate\Routing\Route
     */

    public function addResourceRecycle($name, $base, $controller, $options)
    {
        return $this->addResourceRoute($name, $base, $controller, $options, 'recycle', "/" . "{" . $this->getResourceUri($name) . "}", 'post');
    }

    /**
     * Adding recycle method for a resourcful route
     *
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @param  string   $method
     * @return \Illuminate\Routing\Route
     */

    public function addResourceRestore($name, $base, $controller, $options)
    {
        return $this->addResourceRoute($name, $base, $controller, $options, 'restore', "/" . "{" . $this->getResourceUri($name) . "}", 'post');
    }

    /**
     * Adding recycle method for a resourcful route
     *
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @param  string   $method
     * @return \Illuminate\Routing\Route
     */

    public function addResourceChange_status($name, $base, $controller, $options)
    {
        return $this->addResourceRoute($name, $base, $controller, $options, 'change_status', "/" . "{" . $this->getResourceUri($name) . "}", 'get');
    }

    /**
     * Adding recycle method for a resourcful route
     *
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @param  string   $method
     * @return \Illuminate\Routing\Route
     */

    public function addResourceSort($name, $base, $controller, $options)
    {
        return $this->addResourceRoute($name, $base, $controller, $options, 'sort', '', 'post');
    }

    /**
     * Adding recycle method for a resourcful route
     *
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @param  string   $method
     * @return \Illuminate\Routing\Route
     */

    public function addResourceGet_sort($name, $base, $controller, $options)
    {
        // $uri = $this->getResourceUri($name) . '/get_sort' ;

        // $action = $this->getResourceAction($name, $controller, 'get_sort', $options);

        // return $this->router->get($uri, $action);
        return $this->addResourceRoute($name, $base, $controller, $options, 'get_sort', '', 'get');
    }

    /**
     * Adding update method for a resourcful route
     *
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @param  string   $method
     * @return \Illuminate\Routing\Route
     */

    public function addResourceUpdate($name, $base, $controller, $options)
    {
        return $this->addResourceRoute($name, $base, $controller, $options, 'update', "/" . "{" . $this->getResourceUri($name) . "}", 'post');
    }

    /**
     * Adding update method for a resourcful route
     *
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @param  string   $method
     * @return \Illuminate\Routing\Route
     */

    public function addResourceDestroy($name, $base, $controller, $options)
    {
        return $this->addResourceRoute($name, $base, $controller, $options, 'destroy', "/" . "{" . $this->getResourceUri($name) . "}", 'get');
    }
}
