<?php

namespace App\Repository;

use App\Entity\MessageContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessageContact>
 *
 * @method MessageContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageContact[]    findAll()
 * @method MessageContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageContact::class);
    }




    public function findMessageContactByUserId($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                    message_contact.id AS message_contact_id,
                    message_contact.user_product_id AS message_contact_user_product_id,
                    message_contact.user_send_id AS message_contact_user_send_id,
                    message_contact.user_receive_id AS message_contact_user_receive_id,
                    message_contact.message AS message_contact_message,
                    message_contact.created_at AS message_contact_created_at,
                    message_contact.updated_at AS message_contact_updated_at,
                    message_contact.conversation_code AS message_contact_conversation_code,
                    message_contact.user_send_id_status AS message_contact_user_send_id_status,
                    message_contact.user_receive_id_status AS message_contact_user_receive_id_status,
                    message_contact.user_send_id_read AS message_contact_user_send_id_read,
                    message_contact.user_receive_id_read AS message_contact_user_receive_id_read,
                    
                    product.id AS product_id,
                    product.name AS product_name,
                    product.description AS product_description,
                    product.created_at AS product_created_at,
                    product.updated_at AS product_updated_at,
                    product.city AS product_city,
                    product.user_id AS product_user_id,
                    product.delivery AS product_delivery,
                    product.latitude AS product_latitude,
                    product.longitude AS product_longitude,
                    product.zip AS product_zip,
                    product.phone AS product_phone,
                    product.email AS product_email,
                    product.category_id AS product_category_id,
                
                    t1.id AS user_product_id,
                    t1.name AS user_product_name,
                    t1.firstname AS user_product_firstname,
                    t1.email AS user_product_email,
                    t1.phone AS user_product_phone,
                    
                    t2.id AS user_send_id,
                    t2.name AS user_send_name,
                    t2.firstname AS user_send_firstname,
                    t2.email AS user_send_email,
                    t2.phone AS user_send_phone,
                    
                    t3.id AS user_receive_id,
                    t3.name AS user_receive_name,
                    t3.firstname AS user_receive_firstname,
                    t3.email AS user_receive_email,
                    t3.phone AS user_receive_phone
                    
                FROM message_contact
                
                INNER JOIN product ON product.id = message_contact.product_id
                INNER JOIN user t1 ON t1.id = message_contact.user_product_id
                INNER JOIN user t2 ON t2.id = message_contact.user_send_id
                INNER JOIN user t3 ON t3.id = message_contact.user_receive_id
                
                WHERE 
                      t2.id = :user_product_id OR
                      t3.id = :user_product_id';

        $result = $conn->executeQuery($sql, [
            'user_product_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }

















    /*--- GET LAST MESSAGE INSERTED ---*/

    public function lastMessageInserted($user_send_id, $user_receive_id, $product_id, $conversation_code, $created_at) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                *
                FROM message_contact
                WHERE 
                    user_send_id = :user_send_id
                AND user_receive_id = :user_receive_id
                AND product_id = :product_id
                AND conversation_code = :conversation_code
                AND created_at = :created_at';

        $result = $conn->executeQuery($sql, [
            'user_send_id' => $user_send_id,
            'user_receive_id' => $user_receive_id,
            'product_id' => $product_id,
            'conversation_code' => $conversation_code,
            'created_at' => $created_at
        ]);

        return $result->fetchAllAssociative();
    }















    /*--- FIND MESSAGE BY CONVERSATION CODE ---*/

    public function findMessageContactByConversationCode($conversation_code) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                    message_contact.id AS message_contact_id,
                    message_contact.user_product_id AS message_contact_user_product_id,
                    message_contact.user_send_id AS message_contact_user_send_id,
                    message_contact.user_receive_id AS message_contact_user_receive_id,
                    message_contact.message AS message_contact_message,
                    message_contact.created_at AS message_contact_created_at,
                    message_contact.updated_at AS message_contact_updated_at,
                    message_contact.conversation_code AS message_contact_conversation_code,
                    message_contact.user_send_id_status AS message_contact_user_send_id_status,
                    message_contact.user_receive_id_status AS message_contact_user_receive_id_status,
                    message_contact.user_send_id_read AS message_contact_user_send_id_read,
                    message_contact.user_receive_id_read AS message_contact_user_receive_id_read

                FROM message_contact
                
                WHERE message_contact.conversation_code = :conversation_code';

        $result = $conn->executeQuery($sql, [
            'conversation_code' => $conversation_code
        ]);

        return $result->fetchAllAssociative();
    }













    /*--- UPDATE STATUS ---*/

    public function updateStatus($message_contact_id, $column_status, $other_column_status) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE
                message_contact
                SET user_send_id_status = 

                    (CASE
                        WHEN :column_status = "user_send_id_status" THEN "hidden"
                        WHEN :column_status = "user_receive_id_status" THEN "visible"
                        ELSE "visible"
                    END),

                    user_receive_id_status =

                    (CASE
                        WHEN :column_status = "user_send_id_status" THEN "visible"
                        WHEN :column_status = "user_receive_id_status" THEN "hidden"
                        ELSE "visible"
                    END)

                WHERE id = :message_contact_id';

        $result = $conn->executeQuery($sql, [
            'column_status' => $column_status,
            'message_contact_id' => $message_contact_id
        ]);
    }
















    /*--- UPDATE STATUS SEND ID ---*/

    public function updateStatusSendId($message_contact_id, $other_column_status) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE
                message_contact
                SET user_send_id_status = "hidden",

                    user_receive_id_status = :other_column_status

                WHERE id = :message_contact_id';

        $result = $conn->executeQuery($sql, [
            'other_column_status' => $other_column_status,
            'message_contact_id' => $message_contact_id
        ]);
    }








    /*--- UPDATE STATUS RECEIVE ID ---*/

    public function updateStatusReceiveId($message_contact_id, $other_column_status) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE
                message_contact
                SET user_send_id_status = :other_column_status,

                    user_receive_id_status = "hidden"

                WHERE id = :message_contact_id';

        $result = $conn->executeQuery($sql, [
            'other_column_status' => $other_column_status,
            'message_contact_id' => $message_contact_id
        ]);
    }


















    /*--- UPDATE READ RECEIVE BY MESSAGE CONTACT ID ---*/

    public function updateUserReceiveIdRead($message_contact_id, $conversation_code, $user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE
                message_contact
                SET user_receive_id_read = "true"

                WHERE id = :message_contact_id
                AND
                      conversation_code = :conversation_code
                AND
                      user_receive_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'message_contact_id' => $message_contact_id,
            'conversation_code' => $conversation_code,
            'user_id' => $user_id
        ]);
    }












    /*--- DELETE MESSAGE_CONTACT BY PRODUCT_ID ---*/

    public function deleteMessageContactByProductId($product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM message_contact
                WHERE product_id = :product_id';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id
        ]);
    }











    /*--- DELETE MESSAGE_CONTACT BY USER_SEND_ID ---*/

    public function deleteMessageContactByUserSendId($user_send_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM message_contact
                WHERE user_send_id = :user_send_id';

        $result = $conn->executeQuery($sql, [
            'user_send_id' => $user_send_id
        ]);
    }





    /*--- DELETE MESSAGE_CONTACT BY USER_RECEIVE_ID ---*/

    public function deleteMessageContactByUserReceiveId($user_receive_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM message_contact
                WHERE user_receive_id = :user_receive_id';

        $result = $conn->executeQuery($sql, [
            'user_receive_id' => $user_receive_id
        ]);
    }

    //    /**
    //     * @return MessageContact[] Returns an array of MessageContact objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MessageContact
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
