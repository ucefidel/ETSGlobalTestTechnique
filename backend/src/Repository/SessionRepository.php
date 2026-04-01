<?php

namespace App\Repository;

use App\Document\Session;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class SessionRepository extends ServiceDocumentRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function countAll(): int
    {
        return  $this->createQueryBuilder()
            ->count()
            ->getQuery()
            ->execute();
    }

    public function save(Session $session): void
    {
        $this->getDocumentManager()->persist($session);
        $this->getDocumentManager()->flush();
    }

    public function delete(Session $session): void
    {
        $this->getDocumentManager()->remove($session);
        $this->getDocumentManager()->flush();
    }

    public function findPagination(int $offset, int $limit, bool $availableOnly = false): array
    {

        $query = $this->createQueryBuilder();

        if($availableOnly){
            $query->field('availableSeats')->gt(0);
        }

        return $query->skip($offset)
            ->limit($limit)
            ->getQuery()
            ->toArray();
    }

    public function countPagination( bool $availableOnly = false): int
    {
        $query = $this->createQueryBuilder();

        if($availableOnly){
            $query->field('availableSeats')->gt(0);
        }

        return $query->count()
            ->getQuery()
            ->execute();
    }
}
