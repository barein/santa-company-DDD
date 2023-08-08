<?php

declare(strict_types=1);

namespace Fixtures\Factory\LetterProcessing;

use App\LetterProcessing\Shared\Domain\Letter;
use App\Shared\Domain\Address;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Letter>
 *
 * @method        Letter|Proxy create(array|callable $attributes = [])
 * @method static Letter|Proxy createOne(array $attributes = [])
 * @method static Letter|Proxy find(array|mixed|object $criteria)
 * @method static Letter|Proxy findOrCreate(array $attributes)
 * @method static Letter|Proxy first(string $sortedField = 'id')
 * @method static Letter|Proxy last(string $sortedField = 'id')
 * @method static Letter|Proxy random(array $attributes = [])
 * @method static Letter|Proxy randomOrCreate(array $attributes = []))
 * @method static Letter[]|Proxy[] all()
 * @method static Letter[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Letter[]&Proxy[] createSequence(callable|iterable $sequence)
 * @method static Letter[]|Proxy[] findBy(array $attributes)
 * @method static Letter[]|Proxy[] randomRange(int $min, int $max, array $attributes = []))
 * @method static Letter[]|Proxy[] randomSet(int $number, array $attributes = []))
 */
final class LetterFactory extends ModelFactory
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
            'receivingDate' => new \DateTimeImmutable(),
            'senderAddress' => Address::from(
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
            // ->afterInstantiate(function(Letter $Letter) {})
        ;
    }

    protected static function getClass(): string
    {
        return Letter::class;
    }
}
