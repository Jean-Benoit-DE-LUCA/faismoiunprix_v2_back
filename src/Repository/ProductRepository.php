<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    /*--- GET PRODUCT BY ID ---*/

    public function getProductById($product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
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
            product.initial_created_at AS product_initial_created_at,

            user.name AS user_offer_name,
            user.firstname AS user_offer_firstname,
            user.email AS user_offer_email,
            user.phone AS user_offer_phone
            
            FROM product

            INNER JOIN user ON user.id = product.user_id
            
            WHERE product.id = :product_id';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id
        ]);

        return $result->fetchAllAssociative();
    }













    /*--- GET PRODUCT BY ID => OBJ ---*/

    public function findProductByIdObj($product_id) {

        $qb = $this->createQueryBuilder('p')
              ->where('p.id = :product_id')
              ->setParameter('product_id', $product_id);

        $query = $qb->getQuery();

        return $query->execute();
    }






    /*--- GET ALL PRODUCTS ---*/

    public function getAllProducts($limit, $offset) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
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
                product.initial_created_at AS product_initial_created_at
                
                FROM product
                
                LIMIT ' . $limit . ' ' .
                
                'OFFSET ' . $offset;

        $result = $conn->executeQuery($sql);

        return $result->fetchAllAssociative();
    }







    /*--- GET MAX PRODUCTS ---*/

    public function getMaxProducts() {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                COUNT(*) AS max_length
                FROM
                product';

        $result = $conn->executeQuery($sql);

        return $result->fetchAssociative();
    }







    /*--- GET ALL PRODUCTS BY USER_ID ALTERNATIVE ---*/

    public function getProductsByUserIdAlt($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
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
                product.initial_created_at AS product_initial_created_at
                
                FROM product
                
                WHERE product.user_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }







    /*--- GET ALL PRODUCTS BY NAME ---*/

    public function getAllProductsByName($name) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
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
                product.initial_created_at AS product_initial_created_at

                FROM
                product

                WHERE name

                LIKE :name';

        $result = $conn->executeQuery($sql, [
            'name' => '%' . $name . '%'
        ]);

        return $result->fetchAllAssociative();
    }















    /*--- GET ALL PRODUCTS BY NAME LIMIT ---*/

    public function getAllProductsByNameLimit($name, $limit, $offset) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
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
                product.initial_created_at AS product_initial_created_at

                FROM
                product

                WHERE name

                LIKE :name
                
                LIMIT ' . $limit . ' ' .
                
                'OFFSET ' . $offset;

        $result = $conn->executeQuery($sql, [
            'name' => '%' . $name . '%'
        ]);

        return $result->fetchAllAssociative();
    }









    /*--- INSERT PRODUCT ---*/

    public function insertProduct($name, $category_id, $description, $created_at, $updated_at, $city, $user_id, $delivery, $latitude, $longitude, $zip, $phone, $email) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT
                INTO product (name, category_id, description, created_at, updated_at, city, user_id, delivery, latitude, longitude, zip, phone, email, initial_created_at)
                VALUES (:name, :category_id, :description, :created_at, :updated_at, :city, :user_id, :delivery, :latitude, :longitude, :zip, :phone, :email, :initial_created_at)';

        $result = $conn->executeQuery($sql, [
            'name' => $name,
            'category_id' => $category_id,
            'description' => $description,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'city' => $city,
            'user_id' => $user_id,
            'delivery' => $delivery,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'zip' => $zip,
            'phone' => $phone,
            'email' => $email,
            'initial_created_at' => $created_at
        ]);
    }










    /*--- GET LAST ROW INSERTED ---*/

    public function getLastProductInserted($name, $created_at, $city, $user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                *
                FROM
                product
                WHERE 
                name = :name AND
                created_at = :created_at AND
                city = :city AND
                user_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'name' => $name,
            'created_at' => $created_at,
            'city' => $city,
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }











    /*--- GET PRODUCT DISTANCE FROM USER INPUT ---*/

    public function getDistance($latitudeInput, $longitudeInput) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
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
                product.initial_created_at AS product_initial_created_at,
                
                (

                    ROUND
                    (
                    (
                    ACOS
                    (
                    SIN(RADIANS(product.latitude)) * SIN(RADIANS(:latitudeInput)) + COS(RADIANS(product.latitude)) * COS(RADIANS(:latitudeInput)) * COS(RADIANS(product.longitude) - RADIANS(:longitudeInput))
                    ) * 6371)
                    , 2
                    ) 

                    +
                 
                    ROUND
                    (
                    (
                    ACOS
                    (
                        SIN(RADIANS(product.latitude)) * SIN(RADIANS(:latitudeInput)) + COS(RADIANS(product.latitude)) * COS(RADIANS(:latitudeInput)) * COS(RADIANS(product.longitude) - RADIANS(:longitudeInput))
                    ) * 6371)
                    , 2
                    ) * 20 / 100

                ) as product_distance
                
                FROM product';

        $result = $conn->executeQuery($sql, [
            'latitudeInput' => $latitudeInput,
            'longitudeInput' => $longitudeInput
        ]);

        return $result->fetchAllAssociative();
    }
















    /*--- GET PRODUCTS BY USER ID ---*/

    public function getProductsByUserId($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT

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
                product.initial_created_at AS product_initial_created_at,

                photo.id AS photo_id,
                photo.photo_list AS photo_photo_list,
                photo.product_id AS photo_product_id,
                photo.created_at AS photo_created_at,
                photo.updated_at AS photo_updated_at,

                u1.name AS user_offer_name,
                u1.firstname AS user_offer_firstname,
                u1.email AS user_offer_email,
                u1.phone AS user_offer_phone

                
                FROM
                product

                INNER JOIN photo ON photo.product_id = product.id

                INNER JOIN user u1 ON u1.id = product.user_id

                WHERE product.user_id = :user_id

                ORDER BY product.created_at DESC';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }







    



    /*--- UPDATE DATA PRODUCT ---*/

    public function updateProduct($name, $description, $updated_at, $city, $delivery, $latitude, $longitude, $zip, $phone, $category_id, $email,
        $product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE
                product

                SET name = :name,
                    description = :description,
                    updated_at = :updated_at,
                    city = :city,
                    delivery = :delivery,
                    latitude = :latitude,
                    longitude = :longitude,
                    zip = :zip,
                    phone = :phone,
                    category_id = :category_id,
                    email = :email
                    
                WHERE id = :product_id';

        $result = $conn->executeQuery($sql, [
            'name' => $name,
            'description' => $description,
            'updated_at' => $updated_at,
            'city' => $city,
            'delivery' => $delivery,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'zip' => $zip,
            'phone' => $phone,
            'category_id' => $category_id,
            'email' => $email,
            'product_id' => $product_id
        ]);
    }















    /*--- DELETE PRODUCT ---*/

    public function deleteProduct($product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM product
                WHERE id = :product_id';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id
        ]);
    }





    /*--- DELETE PRODUCT BY USER_ID ---*/

    public function deleteProductByUserId($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM product
                WHERE user_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);
    }


















    // TO TEST //

    /*--- RENEW PRODUCT ---*/

    public function renewProduct($product_id_renew, $product_created_at_renew) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE product

                SET
                
                    created_at = :product_created_at_renew
                    
                WHERE id = :product_id_renew';

        $result = $conn->executeQuery($sql, [
            'product_created_at_renew' => $product_created_at_renew,
            'product_id_renew' => $product_id_renew
        ]);
    }

















    


    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
