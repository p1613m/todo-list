<?php

namespace App\Core;

use App\Core\Abstracts\User;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Routing\Router;
use Dotenv\Dotenv;

/**
 * Application
 */
final class Application
{
    public static string $ROOT_PATH;
    public static Application $app;

    public Router $router;
    public Request $request;
    public Response $response;
    public View $view;
    public Session $session;
    public ?User $user = null;
    public array $config;

    /**
     * @param string $rootPath
     */
    public function __construct(string $rootPath)
    {
        self::$ROOT_PATH = $rootPath;
        self::$app = $this;

        $this->config = $this->getConfig();
        $this->session = new Session();
        $this->user = null;
        $this->authUser();

        $this->request = new Request($this);
        $this->response = new Response($this);
        $this->router = new Router($this);
        $this->view = new View($this);
    }

    /**
     * Get config from .env
     *
     * @return array[]
     */
    private function getConfig(): array
    {
        $dotenv = Dotenv::createImmutable(self::$ROOT_PATH);
        $dotenv->load();

        return $_ENV;
    }

    /**
     * Run application
     *
     * @return void
     */
    public function run(): void
    {
        $this->router->fillRoutes();

        echo $this->router->resolve();
    }

    /**
     * Login user
     *
     * @param $id
     * @return void
     */
    public function authUser($id = null): void
    {
        $userClass = $this->config['USER_CLASS'];

        if ($id) {
            $this->session->set('user_id', $id);
        }

        if (class_exists($userClass)) {
            $this->user = ($userClass)::query()->findById($this->session->get('user_id') ?? 0);
        }
    }

    /**
     * Logout user
     *
     * @return void
     */
    public function logout(): void
    {
        $this->session->unset('user_id');
    }
}