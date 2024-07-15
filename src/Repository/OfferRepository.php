<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }





    /* --- GET OFFER BY PRODUCT ID ORDER BY OFFERPRICE DESC --- */

    public function getOfferByProductId($product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
        
                offer.id AS offer_id,
                offer.offerprice AS offer_offerprice,
                offer.product_id AS offer_product_id,
                offer.user_offer AS offer_user_offer,
                offer.created_at AS offer_created_at,
                offer.updated_at AS offer_updated_at,
                offer.status AS offer_status
                
                FROM offer WHERE offer.product_id = :product_id
                
                ORDER BY offer.offerprice DESC';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id
        ]);

        return $result->fetchAllAssociative();
    }











    /*--- GET OFFER BY PRODUCT ID + USER OFFER DATA + ORDER BY OFFERPRICE DESC ---*/

    public function getOfferByProductIdUserData($product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
        
                offer.id AS offer_id,
                offer.offerprice AS offer_offerprice,
                offer.product_id AS offer_product_id,
                offer.user_offer AS offer_user_offer,
                offer.created_at AS offer_created_at,
                offer.updated_at AS offer_updated_at,
                offer.status AS offer_status,
                
                user.name AS user_offer_name,
                user.firstname AS user_offer_firstname,
                user.email AS user_offer_email
                
                FROM offer 
                
                INNER JOIN user ON user.id = offer.user_offer
                
                WHERE offer.product_id = :product_id
                
                ORDER BY offer.offerprice DESC';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id
        ]);

        return $result->fetchAllAssociative();
    }











    /* --- INSERT OFFER --- */

    public function insertOffer($product_id, $offerprice, $user_offer, $created_at, $updated_at, $status = 'pending') {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT
                INTO offer (product_id, offerprice, user_offer, created_at, updated_at, status)
                VALUES (:product_id, :offerprice, :user_offer, :created_at, :updated_at, :status)';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id,
            'offerprice' => $offerprice,
            'user_offer' => $user_offer,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'status' => $status
        ]);
    }














    /*--- GET RECEIVED OFFER BY USER_ID ORDER BY CREATED_AT ---*/

    public function getReceivedOfferByUser($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                offer.id AS offer_id,
                offer.offerprice AS offer_offerprice,
                offer.product_id AS offer_product_id,
                offer.user_offer AS offer_user_offer,
                offer.created_at AS offer_created_at,
                offer.updated_at AS offer_updated_at,
                offer.status AS offer_status,

                product.id AS product_id,
                product.name AS product_name,
                product.category_id AS product_category_id,
                product.description AS product_description,
                product.city AS product_city,
                product.zip AS product_zip,
                product.phone AS product_phone,
                product.email AS product_email,
                product.latitude AS product_latitude,
                product.longitude AS product_longitude,
                product.delivery AS product_delivery,
                product.user_id AS product_user_id,
                product.created_at AS product_created_at,
                product.updated_at AS product_updated_at,

                photo.id AS photo_id,
                photo.photo_list AS photo_photo_list,
                photo.product_id AS photo_product_id,
                photo.created_at AS photo_created_at,
                photo.updated_at AS photo_updated_at,

                u1.name AS user_offer_name,
                u1.firstname AS user_offer_firstname,
                u1.email AS user_offer_email,
                u1.phone AS user_offer_phone,

                u2.name AS user_product_name,
                u2.firstname AS user_product_firstname,
                u2.email AS user_product_email,
                u2.phone AS user_product_phone

                
                FROM
                offer

                INNER JOIN product ON product.id = offer.product_id
                INNER JOIN photo ON photo.product_id = offer.product_id

                INNER JOIN user u1 ON u1.id = offer.user_offer
                INNER JOIN user u2 ON u2.id = product.user_id

                WHERE (offer.user_offer != :user_id OR offer.offerprice != NULL)

                AND (product.user_id = :user_id)

                ORDER BY offer.created_at DESC';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }










    /*--- GET SENT OFFER BY USER_ID ORDER BY CREATED_AT ---*/

    public function getSentOfferByUser($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                offer.id AS offer_id,
                offer.offerprice AS offer_offerprice,
                offer.product_id AS offer_product_id,
                offer.user_offer AS offer_user_offer,
                offer.created_at AS offer_created_at,
                offer.updated_at AS offer_updated_at,
                offer.status AS offer_status,

                product.id AS product_id,
                product.name AS product_name,
                product.category_id AS product_category_id,
                product.description AS product_description,
                product.city AS product_city,
                product.zip AS product_zip,
                product.phone AS product_phone,
                product.email AS product_email,
                product.latitude AS product_latitude,
                product.longitude AS product_longitude,
                product.delivery AS product_delivery,
                product.user_id AS product_user_id,
                product.created_at AS product_created_at,
                product.updated_at AS product_updated_at,

                photo.id AS photo_id,
                photo.photo_list AS photo_photo_list,
                photo.product_id AS photo_product_id,
                photo.created_at AS photo_created_at,
                photo.updated_at AS photo_updated_at,

                u1.name AS user_offer_name,
                u1.firstname AS user_offer_firstname,
                u1.email AS user_offer_email,
                u1.phone AS user_offer_phone,

                u2.name AS user_product_name,
                u2.firstname AS user_product_firstname,
                u2.email AS user_product_email,
                u2.phone AS user_product_phone

                
                FROM
                offer

                INNER JOIN product ON product.id = offer.product_id
                INNER JOIN photo ON photo.product_id = offer.product_id

                INNER JOIN user u1 ON u1.id = offer.user_offer
                INNER JOIN user u2 ON u2.id = product.user_id

                WHERE (offer.user_offer = :user_id OR offer.offerprice != NULL)

                AND (product.user_id != :user_id)

                ORDER BY offer.created_at DESC';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }












    /*--- UPDATE OFFER STATUS ---*/

    public function updateOfferStatus($offer_id, $response/*, $updated_at */) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE
                offer
                SET
                status = :status/* ,
                updated_at = :updated_at */
                WHERE id = :offer_id';

        $result = $conn->executeQuery($sql, [
            'status' => $response,
            /*'updated_at' => $updated_at, */
            'offer_id' => $offer_id
        ]);
    }















    /*--- UPDATE OFFER MIN ---*/

    public function updateOfferMin($offer_id, $offerprice, $updated_at) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE
                offer
                SET offerprice = :offerprice,
                    updated_at = :updated_at
                WHERE id = :offer_id';

        $result = $conn->executeQuery($sql, [
            'offerprice' => $offerprice,
            'updated_at' => $updated_at,
            'offer_id' => $offer_id
        ]);
    }















    /*--- DELETE OFFER BY PRODUCT_ID ---*/

    public function deleteOfferByProductId($product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM offer
                WHERE product_id = :product_id';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id
        ]);
    }

    //    /**
    //     * @return Offer[] Returns an array of Offer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Offer
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
