<?php

namespace App\Repository;

use App\Document\User;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class UserRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->createQueryBuilder()
            ->field('email')->equals($email)
            ->getQuery()
            ->getSingleResult();
    }

    public function save(User $user)
    {
        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();
    }
}
