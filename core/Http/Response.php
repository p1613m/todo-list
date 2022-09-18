<?php

namespace App\Core\Http;

use App\Core\Application;

class Response
{
    public Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Set header status code for response
     *
     * @param int $code
     * @return void
     */
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    /**
     * Redirect with exit
     *
     * @param $url
     * @return void
     */
    public function redirect($url): void
    {
        $this->setStatusCode(301);

        header("Location: $url");
        exit;
    }

    /**
     * Redirect to back
     *
     * @return void
     */
    public function back(): void
    {
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Redirect to route by name
     *
     * @param $route
     * @return void
     */
    public function redirectRoute($route): void
    {
        $this->redirect($this->app->router->getUrl($route));
    }
}