<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Infrastructure;

use App\ChildWatching\Shared\Domain\Child;
use App\ChildWatching\Shared\Domain\ChildRepositoryInterface;
use App\Shared\Domain\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @extends ServiceEntityRepository<Child>
 *
 * @method null|Child find($id, $lockMode = null, $lockVersion = null)
 * @method null|Child findOneBy(array $criteria, array $orderBy = null)
 * @method Child[]    findAll()
 * @method Child[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineChildRepository extends ServiceEntityRepository implements ChildRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Child::class);
    }

    public function getByUlid(Ulid $childUlid): Child
    {
        $child = $this->findOneBy(['ulid' => $childUlid]);

        if ($child === null) {
            throw new NotFoundException(sprintf(
                'Child %s could not be found',
                $childUlid,
            ));
        }

        return $child;
    }

    public function add(Child $child): void
    {
        $this->getEntityManager()->persist($child);
    }
}
