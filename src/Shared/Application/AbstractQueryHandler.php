<?php

declare(strict_types=1);

namespace App\Shared\Application;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractQueryHandler
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getResult(ApiVersion $apiVersion, object $dto): object
    {
        return $this->getReadModelHydrator($apiVersion)->hydrate($dto);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getReadModelHydrator(ApiVersion $apiVersion): ReadModelHydratorInterface
    {
        for ($i = $apiVersion->getVersion(); $i >= ApiVersion::VERSION_MIN; --$i) {
            $className = preg_replace("#(Query\D+)#", sprintf('ReadModel\V%s\ReadModelHydrator', $i), static::class);

            if ($className !== null && class_exists($className) && $this->container->has($className)) {
                /** @var ReadModelHydratorInterface $readModelHydrator */
                $readModelHydrator = $this->container->get($className);

                return $readModelHydrator;
            }
        }

        throw new \Exception(sprintf('No ReadModel found for api version %s', $apiVersion->getVersion()));
    }
}