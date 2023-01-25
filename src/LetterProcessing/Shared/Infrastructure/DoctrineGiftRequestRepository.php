<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Infrastructure;

use App\LetterProcessing\Shared\Domain\GiftRequest;
use App\LetterProcessing\Shared\Domain\GiftRequestRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GiftRequest>
 *
 * @method null|GiftRequest find($id, $lockMode = null, $lockVersion = null)
 * @method null|GiftRequest findOneBy(array $criteria, array $orderBy = null)
 * @method GiftRequest[]    findAll()
 * @method GiftRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineGiftRequestRepository extends ServiceEntityRepository implements GiftRequestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GiftRequest::class);
    }
}
