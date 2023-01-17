<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain\Exception;

use App\Contexts\Shared\Domain\HttpCode;

abstract class AbstractBaseException extends \Exception
{
    protected HttpCode $httpStatusCode;

    protected string $codeError;

    /**
     * @var array<mixed>
     */
    protected array $metadatas = [];

    public function __construct(HttpCode $httpStatusCode, string $message, string $code)
    {
        parent::__construct($message);
        $this->httpStatusCode = $httpStatusCode;
        $this->codeError = $code;
        $this->message = $message;
    }

    public function addMetadatas(string $key, mixed $value): void
    {
        $this->metadatas[$key] = $value;
    }

    public function toJson(): string
    {
        $data = [
            'code' => $this->codeError,
            'message' => $this->message,
            'metadatas' => $this->metadatas,
        ];

        return (string) json_encode($data);
    }

    public function getHttpStatusCode(): HttpCode
    {
        return $this->httpStatusCode;
    }
}
