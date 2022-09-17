<?php

namespace App\Core\Abstracts;

use App\Core\Application;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Session;

/**
 * Base controller
 */
abstract class Controller
{
    public ?string $layout;
    public Application $app;
    public Request $request;
    public Response $response;
    public Session $session;

    public function __construct()
    {
        $this->app = Application::$app;
        $this->request = $this->app->request;
        $this->response = $this->app->response;
        $this->session = $this->app->session;
    }

    /**
     * Call view
     *
     * @param string $fileName
     * @param array $variables
     * @return string
     */
    public function view(string $fileName, array $variables = []): string
    {
        return $this->app->view->render($fileName, $this->layout, $variables);
    }

    /**
     * Redirect to route by name
     *
     * @param $route
     * @return void
     */
    public function redirect($route): void
    {
        $this->response->redirectRoute($route);
    }

}