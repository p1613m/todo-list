<?php

namespace App\Core\Routing;

use App\Core\Application;
use App\Core\Http\Request;
use App\Core\Http\Response;

/**
 * Router
 */
class Router
{
    public Request $request;
    public Response $response;
    public array $routes = [];

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Push route
     *
     * @param Route $route
     * @return void
     */
    public function push(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * Call a route
     *
     * @return mixed
     */
    public function resolve(): mixed
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $route = array_reduce($this->routes, function ($carry, Route $routeModel) use ($url, $method) {
            if ($routeModel->method === $method && $routeModel->url === $url) {
                $carry = $routeModel;
            }
            return $carry;
        });

        if ($route) {
            if (!$this->isMiddlewareAccess($route)) {
                $loginRoute = Application::$app->config['LOGIN_ROUTE'] ?? '/';
                $this->response->redirectRoute(!Application::$app->user ? $loginRoute : '/');
            }

            $action = $route->action;
            if (is_array($action)) {
                $action[0] = new $action[0];
            }

            if (is_callable($action)) {
                return call_user_func($action, $this->request);
            }
        }

        $this->response->redirectRoute('/');
    }

    /**
     * Fill all routes form /app/routes.php
     *
     * @return void
     */
    public function fillRoutes(): void
    {
        require_once Application::$ROOT_PATH . '/app/routes.php';
    }

    /**
     * Get absolute url by route name
     *
     * @param string $routeName
     * @return string
     */
    public function getUrl(string $routeName): string
    {
        $url = $this->request->getBaseUrl();

        $route = array_reduce($this->routes, function ($carry, Route $routeModel) use ($routeName) {
            if ($routeModel->name === $routeName) {
                $carry = $routeModel;
            }
            return $carry;
        });

        if ($route) {
            $url .= $route->url;
        }

        return $url;
    }

    /**
     * Check user access to route
     *
     * @param Route $route
     * @return bool
     */
    private function isMiddlewareAccess(Route $route): bool
    {
        switch ($route->middleware) {
            case 'guest':
                if (Application::$app->user) {
                    return false;
                }
                break;
            case 'admin':
                if (!Application::$app->user) {
                    return false;
                }
                break;
        }

        return true;
    }
}