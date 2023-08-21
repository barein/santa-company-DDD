<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Infrastructure\Child;

use App\LetterProcessing\Shared\Domain\Child\Child;
use App\LetterProcessing\Shared\Domain\Child\ChildRepositoryInterface;
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

    public function add(Child $child): void
    {
        $this->getEntityManager()->persist($child);
    }

    public function get(Ulid $id): Child
    {
        $child = $this->find($id);

        if ($child === null) {
            throw new NotFoundException(sprintf('Child %s could not be found', $id));
        }

        return $child;
    }

    public function getAll(): array
    {
        return $this->findAll();
    }
}
