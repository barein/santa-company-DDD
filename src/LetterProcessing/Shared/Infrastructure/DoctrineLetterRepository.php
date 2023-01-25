<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Infrastructure;

use App\LetterProcessing\Shared\Domain\Letter;
use App\LetterProcessing\Shared\Domain\LetterRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Letter>
 *
 * @method null|Letter find($id, $lockMode = null, $lockVersion = null)
 * @method null|Letter findOneBy(array $criteria, array $orderBy = null)
 * @method Letter[]    findAll()
 * @method Letter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineLetterRepository extends ServiceEntityRepository implements LetterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Letter::class);
    }
}
