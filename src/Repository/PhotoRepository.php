<?php

namespace App\Repository;

use App\Entity\Photo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Photo>
 *
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }


    /* --- GET PICTURE BY PRODUCT ID --- */

    public function getPictureByProductId($product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
        
        photo.id AS photo_id,
        photo.photo_list AS photo_photo_list,
        photo.product_id AS photo_product_id,
        photo.created_at AS photo_created_at,
        photo.updated_at AS photo_updated_at
        
        FROM photo WHERE photo.product_id = :product_id';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id
        ]);

        return $result->fetchAllAssociative();
    }




    /* --- INSERT PHOTO LIST --- */

    public function insertPhotoList($product_id, $photo_list, $created_at, $updated_at) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT
                INTO photo (product_id, photo_list, created_at, updated_at)
                VALUES (:product_id, :photo_list, :created_at, :updated_at)';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id,
            'photo_list' => $photo_list,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
    }







    /*--- UPDATE PHOTO LIST ---*/

    public function updatePhotoList($product_id, $photo_list, $updated_at) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE
                photo
                SET photo_list = :photo_list,
                    updated_at = :updated_at
                WHERE product_id = :product_id';

        $result = $conn->executeQuery($sql, [
            'photo_list' => $photo_list,
            'updated_at' => $updated_at,
            'product_id' => $product_id
        ]);
    }











    /*--- DELETE PHOTO ROW BY PRODUCT_ID ---*/

    public function deletePhotoByProductId($product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM photo
                WHERE product_id = :product_id';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id
        ]);
    }












    /*--- GET ALL PHOTOS BY USER_ID ---*/

    public function getPictureByUserId($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                    photo.*
                FROM 
                    photo
                INNER JOIN
                    product ON product.id = photo.product_id
                INNER JOIN
                    user ON user.id = product.user_id
                WHERE user.id = :user_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }

    //    /**
    //     * @return Photo[] Returns an array of Photo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Photo
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
