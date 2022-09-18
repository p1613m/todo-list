<?php

namespace App\Core\Http;

use App\Core\Application;
use App\Core\Validator;

class Request
{
    public Application $app;
    public array $parameters;


    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
        return $this->app->config['BASE_URL'] . $url;
    }

    /**
     * Get relative path
     *
     * @return string
     */
    public function getRelativePath(): string
    {
        return $this->app->config['RELATIVE_PATH'];
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
            $this->app->session->setFlash('errors', $validator->getErrors());
            $this->app->session->setFlash('old', $this->parameters);
            $this->app->response->back();
            return false;
        }

        return $validator->validatedParameters();
    }
}