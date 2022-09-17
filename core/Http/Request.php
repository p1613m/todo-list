<?php

namespace App\Core\Http;

use App\Core\Application;
use App\Core\Validator;

class Request
{
    public array $parameters;

    public function __construct()
    {
        $this->parameters = $this->getBody();
    }

    /**
     * Get request parameter
     *
     * @param string $key
     * @param ?string $default
     * @return ?mixed
     */
    public function get(string $key, string $default = null): ?string
    {
        return $this->parameters[$key] ?? $default;
    }

    /**
     * Get request method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get request url
     *
     * @return string
     */
    public function getUrl(): string
    {
        $path = str_replace($this->getRelativePath(), '', $_SERVER['REQUEST_URI']);
        $parametersPosition = strpos($path, '?');

        if($parametersPosition !== false) {
            return substr($path, 0, $parametersPosition);
        }

        return $path;
    }

    /**
     * Get application root url
     *
     * @param string $url
     * @return string
     */
    public function getBaseUrl(string $url = ''): string
    {
        return Application::$app->config['BASE_URL'] . $url;
    }

    /**
     * Get relative path
     *
     * @return string
     */
    public function getRelativePath(): string
    {
        return Application::$app->config['RELATIVE_PATH'];
    }

    /**
     * Prepare and get body parameters
     *
     * @return array
     */
    private function getBody(): array
    {
        $body = [];

        foreach ($_GET + $_POST as $key => $value) {
            $body[$key] = $value;
        }

        return $body;
    }

    /**
     * Make all validation
     *
     * @param array $rules
     * @param array $messages
     * @return array|false
     */
    public function validate(array $rules, array $messages = []): array|false
    {
        $validator = new Validator($this->parameters, $rules, $messages);

        if($validator->hasErrors()) {
            Application::$app->session->setFlash('errors', $validator->getErrors());
            Application::$app->session->setFlash('old', $this->parameters);
            Application::$app->response->back();
            return false;
        }

        return $validator->validatedParameters();
    }
}