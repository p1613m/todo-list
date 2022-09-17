<?php

namespace App\Core\Routing;

use App\Core\Application;

/**
 * Route
 */
class Route
{
    public string $method;
    public string $url;
    public mixed $action;
    public ?string $name;
    public string $middleware = 'all';

    /**
     * @param string $method
     * @param string $url
     * @param $action mixed
     */
    public function __construct(string $method, string $url, mixed $action)
    {
        $this->method = $method;
        $this->url = $url;
        $this->action = $action;

        Application::$app->router->push($this);
    }

    /**
     * Push get route
     *
     * @param string $url
     * @param $action
     * @return static
     */
    public static function get(string $url, $action): self
    {
        return new self('get', $url, $action);
    }

    /**
     * Push post route
     *
     * @param string $url
     * @param $action
     * @return static
     */
    public static function post(string $url, $action): self
    {
        return new self('post', $url, $action);
    }

    /**
     * Set name for route
     *
     * @param string $name
     * @return Route
     */
    public function name(string $name): Route
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set middleware for route
     *
     * @param string $middlewareName
     * @return Route
     */
    public function middleware(string $middlewareName): Route
    {
        $this->middleware = $middlewareName;

        return $this;
    }
}