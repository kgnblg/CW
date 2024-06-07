<?php

namespace App\EventListener;

use App\Exception\ApiException;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        $message = 'Error occured.';
        $status  = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof ApiException) {
            $message = $exception->getMessage();
            $status  = $exception->getCode();
        } elseif (str_contains($exception->getMessage(), 'No route found for')) {
            $message = 'Endpoint not valid.';
            $status  = Response::HTTP_BAD_REQUEST;
        } elseif (str_contains($exception->getMessage(), 'TokenStorage')) {
            $message = 'Not authorized. Check the endpoint and/or credentials.';
            $status  = Response::HTTP_BAD_REQUEST;
        }

        // handle custom exceptions with a general API response
        $event->setResponse(new ApiResponse($message, $status, false));
    }
}
