<?php

declare(strict_types=1);

namespace Fixtures\Factory\ChildWatching;

use App\ChildWatching\Shared\Domain\Action;
use App\ChildWatching\Shared\Domain\ActionDescription;
use App\ChildWatching\Shared\Domain\ActionType;
use App\ChildWatching\Shared\Infrastructure\DoctrineActionRepository;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Action>
 *
 * @method        Action|Proxy create(array|callable $attributes = [])
 * @method static Action|Proxy createOne(array $attributes = [])
 * @method static Action|Proxy find(array|mixed|object $criteria)
 * @method static Action|Proxy findOrCreate(array $attributes)
 * @method static Action|Proxy first(string $sortedField = 'id')
 * @method static Action|Proxy last(string $sortedField = 'id')
 * @method static Action|Proxy random(array $attributes = [])
 * @method static Action|Proxy randomOrCreate(array $attributes = []))
 * @method static DoctrineActionRepository|RepositoryProxy repository()
 * @method static Action[]|Proxy[] all()
 * @method static Action[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Action[]&Proxy[] createSequence(callable|iterable $sequence)
 * @method static Action[]|Proxy[] findBy(array $attributes)
 * @method static Action[]|Proxy[] randomRange(int $min, int $max, array $attributes = []))
 * @method static Action[]|Proxy[] randomSet(int $number, array $attributes = []))
 */
final class ActionFactory extends ModelFactory
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
            'dateTime' => new \DateTimeImmutable(),
            'description' => ActionDescription::fromString(self::faker()->sentence()),
            'type' => self::faker()->randomElement([ActionType::GOOD, ActionType::BAD]),
        ];
    }

    /**
     * @see https://github.com/zenstruck/foundry#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Action $Action) {})
        ;
    }

    protected static function getClass(): string
    {
        return Action::class;
    }
}
