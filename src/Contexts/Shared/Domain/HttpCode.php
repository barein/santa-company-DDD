<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain;

enum HttpCode: string
{
    case HTTP_BAD_REQUEST = '400';
    case UNPROCESSABLE_ENTITY = '422';
    case HTTP_INTERNAL_SERVER_ERROR = '500';
    case HTTP_UNAUTHORIZED = '401';
    case HTTP_NOT_FOUND = '404';
    case HTTP_PERMANENTLY_REDIRECT = '308';
    case HTTP_FORBIDDEN = '403';
    case HTTP_METHOD_NOT_ALLOWED = '405';
    case HTTP_NOT_ACCEPTABLE = '406';
    case HTTP_REQUEST_TIMEOUT = '408';
    case HTTP_CONFLICT = '409';
    case HTTP_GONE = '410';
    case HTTP_LENGTH_REQUIRED = '411';
    case HTTP_REQUEST_ENTITY_TOO_LARGE = '413';
    case HTTP_REQUEST_URI_TOO_LONG = '414';
    case HTTP_UNSUPPORTED_MEDIA_TYPE = '415';
    case HTTP_EXPECTATION_FAILED = '417';
    case HTTP_TOO_MANY_REQUESTS = '429';
    case HTTP_NOT_IMPLEMENTED = '501';
    case HTTP_BAD_GATEWAY = '502';
    case HTTP_SERVICE_UNAVAILABLE = '503';
    case HTTP_GATEWAY_TIMEOUT = '504';
    case HTTP_CREATED = '201';
    case HTTP_ACCEPTED = '202';

    public function toInt(): int
    {
        return (int) $this->value();
    }
}
