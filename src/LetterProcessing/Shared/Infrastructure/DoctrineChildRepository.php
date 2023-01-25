<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Infrastructure;

use App\LetterProcessing\Shared\Domain\Child;
use App\LetterProcessing\Shared\Domain\ChildRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
}
