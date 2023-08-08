<?php

declare(strict_types=1);

namespace Fixtures\Factory\LetterProcessing;

use App\LetterProcessing\Shared\Domain\GiftRequest;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<GiftRequest>
 *
 * @method        GiftRequest|Proxy create(array|callable $attributes = [])
 * @method static GiftRequest|Proxy createOne(array $attributes = [])
 * @method static GiftRequest|Proxy find(array|mixed|object $criteria)
 * @method static GiftRequest|Proxy findOrCreate(array $attributes)
 * @method static GiftRequest|Proxy first(string $sortedField = 'id')
 * @method static GiftRequest|Proxy last(string $sortedField = 'id')
 * @method static GiftRequest|Proxy random(array $attributes = [])
 * @method static GiftRequest|Proxy randomOrCreate(array $attributes = []))
 * @method static GiftRequest[]|Proxy[] all()
 * @method static GiftRequest[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static GiftRequest[]&Proxy[] createSequence(callable|iterable $sequence)
 * @method static GiftRequest[]|Proxy[] findBy(array $attributes)
 * @method static GiftRequest[]|Proxy[] randomRange(int $min, int $max, array $attributes = []))
 * @method static GiftRequest[]|Proxy[] randomSet(int $number, array $attributes = []))
 */
final class GiftRequestFactory extends ModelFactory
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
            'giftName' => self::faker()->randomElement([
                'Blue little car',
                'Wooden horse',
                'Teddy bear',
                'Sophie la girafe',
                'Legos',
                'Playmobil',
                'Billes',
                'Barbie',
                'Furby',
                'Poupée',
                'Pistolet à eau',
                'Figurines',
            ]),
        ];
    }

    /**
     * @see https://github.com/zenstruck/foundry#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(GiftRequest $GiftRequest) {})
        ;
    }

    protected static function getClass(): string
    {
        return GiftRequest::class;
    }
}
