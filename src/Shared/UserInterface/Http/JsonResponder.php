<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Http;

use App\Shared\Domain\HttpStatusCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonResponder
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function response(HttpStatusCode $status, mixed $data = null): JsonResponse
    {
        $json = false;
        if ($data !== null) {
            $data = $this->serializer->serialize($data, 'json');
            $json = true;
        }

        return new JsonResponse(
            $data,
            $status->toInt(),
            [],
            $json
        );
    }
}
