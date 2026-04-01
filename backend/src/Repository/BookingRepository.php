<?php

namespace App\Repository;

use App\Document\Booking;
use App\Document\Session;
use App\Document\User;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class BookingRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function findByUserAndSession(User $user, Session $session): ?Booking
    {
        return $this->createQueryBuilder()
            ->field('user')->references($user)
            ->field('session')->references($session)
            ->getQuery()
            ->getSingleResult();
    }

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder()
            ->field('user')->references($user)
            ->getQuery()
            ->toArray();

    }

    public function save(Booking $booking): void
    {
        $this->getDocumentManager()->persist($booking);
        $this->getDocumentManager()->flush();
    }

    public function delete(Booking $booking): void
    {
        $this->getDocumentManager()->remove($booking);
        $this->getDocumentManager()->flush();
    }

}
