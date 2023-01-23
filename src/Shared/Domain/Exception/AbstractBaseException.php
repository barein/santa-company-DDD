<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\HttpStatusCode;

abstract class AbstractBaseException extends \Exception
{
    protected HttpStatusCode $httpStatusCode;

    protected string $codeError;

    /**
     * @var array<mixed>
     */
    protected array $metadatas = [];

    public function __construct(HttpStatusCode $httpStatusCode, string $message, string $code)
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

    public function getHttpStatusCode(): HttpStatusCode
    {
        return $this->httpStatusCode;
    }
}
