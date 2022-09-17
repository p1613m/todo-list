<?php

namespace App\Core;


use App\Core\Abstracts\User;
use App\Core\Http\Request;
use App\Core\Routing\Router;

/**
 * View rendering and helper methods
 */
class View
{
    public Session $session;
    public Router $router;
    public Request $request;
    public ?User $user;
    public string $title;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->session = $app->session;
        $this->router = $app->router;
        $this->request = $app->request;
        $this->user = $app->user;
    }

    /**
     * Render view
     *
     * @param string $name
     * @param ?string $layoutName
     * @param array $variables
     * @return array|false|string|string[]
     */
    public function render(string $name, ?string $layoutName, array $variables = []): array|bool|string
    {
        $viewContent = $this->readFile($name, $variables);

        return $layoutName ? str_replace('@content', $viewContent, $this->readFile($layoutName)) : $viewContent;
    }

    /**
     * Render file for view
     *
     * @param string $fileName
     * @param array $variables
     * @return false|string
     */
    public function readFile(string $fileName, array $variables = []): bool|string
    {
        ob_start();
        extract($variables);
        require_once Application::$ROOT_PATH . "/views/$fileName.php";
        return ob_get_clean();
    }

    /**
     * Get old values for input fields
     * helper for view
     *
     * @param string $key
     * @param $default
     * @return mixed|null
     */
    public function old(string $key, $default = null): mixed
    {
        return $this->session->getInnerFlash('old', $key) ?? $default;
    }

    /**
     * Get errors for input fields
     * helper for view
     *
     * @param string $key
     * @param ?string $returnString
     * @return mixed
     */
    public function error(string $key, string $returnString = null): mixed
    {
        $error = $this->session->getInnerFlash('errors', $key);
        if ($error && $returnString) {
            return $returnString;
        }

        return $error;
    }

    /**
     * Esc HTML for view
     * helper for view
     *
     * @param string $text
     * @return mixed
     */
    public function esc(mixed $text): string
    {
        return is_string($text) ? htmlspecialchars($text) : '';
    }
}