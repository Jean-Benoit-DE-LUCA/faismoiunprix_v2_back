<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }




    /*--- CHECK IF CITY IN DATABASE ---*/

    public function checkCity($city, $zip) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                *
                FROM city
                WHERE city.city = :city
                AND city.zip = :zip';

        $result = $conn->executeQuery($sql, [
            'city' => $city,
            'zip' => $zip
        ]);

        return $result->fetchAllAssociative();
    }












    /*--- INSERT CITY IN DATABASE ---*/

    public function insertCity($city, $zip, $latitude, $longitude) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT
                INTO city (city, zip, latitude, longitude)
                VALUES (:city, :zip, :latitude, :longitude)';

        $result = $conn->executeQuery($sql, [
            'city' => $city,
            'zip' => $zip,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
    }

    //    /**
    //     * @return City[] Returns an array of City objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?City
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
