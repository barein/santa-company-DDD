<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Infrastructure;

use App\ChildWatching\Shared\Domain\Action;
use App\ChildWatching\Shared\Domain\ActionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @extends ServiceEntityRepository<Action>
 *
 * @method null|Action find($id, $lockMode = null, $lockVersion = null)
 * @method null|Action findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineActionRepository extends ServiceEntityRepository implements ActionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Action::class);
    }

    public function add(Action $action): void
    {
        $this->getEntityManager()->persist($action);
    }

    public function getActionsOfChildThisYear(Ulid $childUlid): array
    {
        $currentYear = (new \DateTimeImmutable())->format('Y');

        /** @var array<Action> $actions */
        $actions = $this->createQueryBuilder('actions')
            ->andWhere('actions.childUlid = :childUlid')
            ->andWhere('actions.dateTime BETWEEN :firstDayCurrentYear AND :lastDayCurrentYear')
            ->setParameter('childUlid', $childUlid)
            ->setParameter('firstDayCurrentYear', new \DateTimeImmutable(sprintf('first day of january %s', $currentYear)))
            ->setParameter('lastDayCurrentYear', new \DateTimeImmutable(sprintf('last day of december %s', $currentYear)))
            ->setParameter('childUlid', $childUlid)
            ->getQuery()
            ->getResult()
        ;

        return $actions;
    }
}
