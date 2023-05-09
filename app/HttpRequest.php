<?php

namespace App;

class HttpRequest
{
    private HttpMethods $method;
    private array $params;

    private mixed $body = [];

    private string $uri;

    /**
     * @param HttpMethods $method
     * @param array $params
     * @param mixed $body
     */
    public function __construct()
    {
        $this->method = HttpMethods::from($_SERVER['REQUEST_METHOD']);
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents('php://input');
            $this->body = json_decode($json, true);
        } else {
            parse_str(file_get_contents("php://input"), $formData);
            $this->body = $formData ?: $_POST;
        }
    }


    /**
     * @return string
     */
    public function get_method(): string
    {
        return $this->method->value;
    }

    /**
     * @return array
     */
    public function get_params(): array
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function get_body(): mixed
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function get_uri(): string
    {
        return $this->uri;
    }

    /**
     * @param array $params
     */
    public function set_params(array $params): void
    {
        $this->params = $params;
    }


}
