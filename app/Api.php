<?php

namespace App;

use App\Route;

class Api
{
    private string $base_path;

    /**
     * @var Route[][]
     */
    private array $routes = [];

    /**
     * @param string $base_path
     */
    public function __construct(string $base_path)
    {
        $this->base_path = $base_path;
    }

    public function add_route (HttpMethods $method, string $uri, callable $callback): void
    {
        $route = new Route($callback, $uri);
        $this->routes[$method->value][] = $route;
    }

    public function run() {
        $request = new HttpRequest();
        $method_routes = $this->routes[$request->get_method()];

        foreach ($method_routes as $route) {
            if (preg_match('/' . preg_quote($this->base_path, '/') . $route. '/',
                $request->get_uri(), $matches)) {

                array_shift($matches);

                $callback = $route->getCallback();
                if ($route->hasParams()) {

                    $params  = array_combine(array_values($route->getParams()), $matches);
                    $request->set_params($params);
                }

                return $callback($request);
            }
        }

        return JsonResponse::methodNotAllowed();
    }


}
