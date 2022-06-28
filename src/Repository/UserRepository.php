<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Webmozart\Assert\Assert;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    /**
     * This method is used by an UniqueEntity constraint of the User entity
     *
     * @param array<string, mixed> $parameters
     */
    public function findByUsernameInsensitive(array $parameters): ?User
    {
        $username = $parameters['username'];
        Assert::stringNotEmpty($username);

        $user = $this->createQueryBuilder('u')
            ->andWhere('LOWER(u.username) = :username')
            ->setParameter('username', strtolower($username))
            ->getQuery()
            ->getOneOrNullResult();

        Assert::nullOrIsInstanceOf($user, User::class);

        return $user;
    }

    /**
     * This method is used by an UniqueEntity constraint of the User entity
     *
     * @param array<string, mixed> $parameters
     */
    public function findByEmailInsensitive(array $parameters): ?User
    {
        $email = $parameters['email'];
        Assert::stringNotEmpty($email);

        $user = $this->createQueryBuilder('u')
            ->andWhere('LOWER(u.email) = :email')
            ->setParameter('email', strtolower($email))
            ->getQuery()
            ->getOneOrNullResult();

        Assert::nullOrIsInstanceOf($user, User::class);

        return $user;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
