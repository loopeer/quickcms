<?php
/**
 * Created by PhpStorm.
 * User: korhsien
 * Date: 2017/2/23
 * Time: 下午4:31
 */

namespace Loopeer\QuickCms\Routing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\ResourceRegistrar as BaseResourceRegistrar;
use Illuminate\Routing\Router;

class ResourceRegistrar extends BaseResourceRegistrar
{
    protected static $modelMap = [];

    public function __construct(Router $router)
    {
        parent::__construct($router);

        $router->matched(function ($route) {
            $name = $route->getName();
            if (isset(static::$modelMap[$name])) {
                $model = static::$modelMap[$name];
                $container = app();
                $container->bind(Model::class, $model);
                $route->setContainer($container);
            }
        });

        $this->resourceDefaults = array_merge(['search'], $this->resourceDefaults);
    }

    protected function getResourceAction($resource, $controller, $method, $options)
    {
        $action = parent::getResourceAction($resource, $controller, $method, $options);

        if (isset($options['model'])) {
            $model = $options['model'];
            static::$modelMap[$action['as']] = $model;
        }

        return $action;
    }

    protected function addResourceSearch($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/search';

        $action = $this->getResourceAction($name, $controller, 'search', $options);

        return $this->router->get($uri, $action);
    }
}