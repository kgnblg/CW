<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends JsonResponse
{
    /**
     * @param string|null $message optional message field for the API response
     * @param bool $httpStatus the HTTP Status of the response
     * @param bool $status represents whether the response contains a successful action result or not
     * @param array $headers
     * @param bool $json
     */
    public function __construct(?string $message = null, int $httpStatus = Response::HTTP_OK, bool $status = true, array $headers = [], bool $json = false)
    {
        parent::__construct($this->format($status, $message), $httpStatus, $headers, $json);
    }

    /**
     * @param bool $status
     * @param string|null $message
     */
    public function format(bool $status, ?string $message = null): array
    {
        $res = [ 'status' => $status ? 'successful' : 'error' ];

        if ($message) {
            $res['message'] = $message;
        }

        return $res;
    }
}