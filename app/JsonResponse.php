<?php
namespace App;

class JsonResponse
{
    private string $contentType = 'application/json';
    private int $status_code;

    /**
     * @param int $status_code
     */
    public function __construct(int $status_code)
    {
        $this->status_code = $status_code;
    }


    private function respond($data = null): void
    {
        header("Content-type: $this->contentType", true, $this->status_code);
        echo json_encode($data);
    }

    static function ok(mixed $data = null): void
    {
        (new self(200))->respond($data);
    }

    static function noContent(): void
    {
        (new self(204))->respond();
    }

    static function created(mixed $data = null): void
    {
        (new self(201))->respond($data);
    }
    static function notFound(): void
    {
        (new self(404))->respond(['error' => 'Not Found']);
    }

    public static function methodNotAllowed(): void
    {
        (new self(405))->respond(['error' => 'Route Not Defined']);
    }

}
