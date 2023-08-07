<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

abstract class AbstractBaseException extends \DomainException
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
    }

    public function addMetadatas(string $key, mixed $value): void
    {
        $this->metadatas[$key] = $value;
    }

    public function getHttpStatusCode(): HttpStatusCode
    {
        return $this->httpStatusCode;
    }

    public function getCodeError(): string
    {
        return $this->codeError;
    }

    /**
     * @return array<mixed>
     */
    public function getMetadatas(): array
    {
        return $this->metadatas;
    }
}
