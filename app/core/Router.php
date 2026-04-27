<?php

class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->add('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->add('POST', $path, $handler);
    }

    public function add($method, $path, $handler)
    {
        $method = strtoupper($method);
        $path = '/' . trim($path, '/');
        if ($path === '//') {
            $path = '/';
        }

        $this->routes[$method][$path] = $handler;
    }

    public function dispatch($method, $path)
    {
        $method = strtoupper($method);
        $path = '/' . trim($path, '/');
        if ($path === '//') {
            $path = '/';
        }

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            return $this->runHandler($handler);
        }

        $controller = new ErrorController();
        return $controller->notFound();
    }

    private function runHandler($handler)
    {
        if (is_callable($handler)) {
            return call_user_func($handler);
        }

        if (is_array($handler) && count($handler) === 2) {
            $class = $handler[0];
            $method = $handler[1];

            if (!class_exists($class)) {
                throw new Exception('Controller class not found: ' . $class);
            }

            $controller = new $class();
            if (!method_exists($controller, $method)) {
                throw new Exception('Method not found: ' . $class . '::' . $method);
            }

            return $controller->$method();
        }

        throw new Exception('Invalid route handler');
    }
}
