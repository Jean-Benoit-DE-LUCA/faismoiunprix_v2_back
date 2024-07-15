<?php

namespace App\Repository;

use App\Entity\Jwt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jwt>
 *
 * @method Jwt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jwt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jwt[]    findAll()
 * @method Jwt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JwtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jwt::class);
    }




    /*--- DELETE JWT ---*/

    public function deleteJwt($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM jwt
                WHERE user_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);
    }










    /*--- INSERT JWT ---*/

    public function insertJwt($user_id, $jwt, $created_at, $updated_at) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT
                INTO jwt (user_id, code, created_at, updated_at)
                VALUES (:user_id, :code, :created_at, :updated_at)';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id,
            'code' => $jwt,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
    }














    /*--- GET JWT ---*/

    public function getJwt($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                *
                FROM jwt
                WHERE user_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }

    //    /**
    //     * @return Jwt[] Returns an array of Jwt objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Jwt
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
