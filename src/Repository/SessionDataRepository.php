<?php

namespace App\Repository;

use App\Entity\SessionData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SessionData>
 *
 * @method SessionData|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionData|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionData[]    findAll()
 * @method SessionData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionData::class);
    }


    /*--- INSERT SESSION DATA ---*/

    public function insertSessionData($session, $user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT
                INTO session_data (session, user_id)
                VALUES (:session, :user_id)';

        $result = $conn->executeQuery($sql, [
            'session' => $session,
            'user_id' => $user_id
        ]);
    }







    /*--- GET SESSION DATA ---*/

    public function getSessionData($session) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                *
                FROM session_data
                WHERE session = :session';

        $result = $conn->executeQuery($sql, [
            'session' => $session
        ]);

        return $result->fetchAllAssociative();
    }












    /*--- DELETE DATA SESSION ---*/

    public function deleteDataSession($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM session_data
                WHERE user_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);
    }

    //    /**
    //     * @return SessionData[] Returns an array of SessionData objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SessionData
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
