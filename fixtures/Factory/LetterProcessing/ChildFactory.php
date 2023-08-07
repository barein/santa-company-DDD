<?php

declare(strict_types=1);

namespace Fixtures\Factory\LetterProcessing;

use App\LetterProcessing\Shared\Domain\Child;
use App\LetterProcessing\Shared\Infrastructure\DoctrineChildRepository;
use App\Shared\Domain\Address;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Child>
 *
 * @method        Child|Proxy create(array|callable $attributes = [])
 * @method static Child|Proxy createOne(array $attributes = [])
 * @method static Child|Proxy find(array|mixed|object $criteria)
 * @method static Child|Proxy findOrCreate(array $attributes)
 * @method static Child|Proxy first(string $sortedField = 'id')
 * @method static Child|Proxy last(string $sortedField = 'id')
 * @method static Child|Proxy random(array $attributes = [])
 * @method static Child|Proxy randomOrCreate(array $attributes = []))
 * @method static DoctrineChildRepository|RepositoryProxy repository()
 * @method static Child[]|Proxy[] all()
 * @method static Child[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Child[]&Proxy[] createSequence(callable|iterable $sequence)
 * @method static Child[]|Proxy[] findBy(array $attributes)
 * @method static Child[]|Proxy[] randomRange(int $min, int $max, array $attributes = []))
 * @method static Child[]|Proxy[] randomSet(int $number, array $attributes = []))
 */
final class ChildFactory extends ModelFactory
{
    /**
     * @see https://github.com/zenstruck/foundry#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://github.com/zenstruck/foundry#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'id' => new Ulid(),
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'address' => Address::from(
                number: self::faker()->randomNumber(3),
                street: self::faker()->streetName(),
                city: self::faker()->city(),
                zipCode: self::faker()->randomNumber(5),
                isoCountryCode: self::faker()->countryISOAlpha3(),
            ),
        ];
    }

    /**
     * @see https://github.com/zenstruck/foundry#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Child $Child) {})
        ;
    }

    protected static function getClass(): string
    {
        return Child::class;
    }
}
