<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }




    /*--- INSERT MESSAGE ---*/

    public function insertMessage($user_id, $user_offer_id, $product_id, $message, $created_at, $updated_at, $offer_id, $user_send_id_read, $user_receive_id_read, $user_receive_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT
                INTO message (user_id, user_offer_id, product_id, message, created_at, updated_at, offer_id, user_send_id_read, user_receive_id_read, user_receive_id)
                VALUES (:user_id, :user_offer_id, :product_id, :message, :created_at, :updated_at, :offer_id, :user_send_id_read, :user_receive_id_read, :user_receive_id)';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id,
            'user_offer_id' => $user_offer_id,
            'product_id' => $product_id,
            'message' => $message,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'offer_id' => $offer_id,
            'user_send_id_read' => $user_send_id_read,
            'user_receive_id_read' => $user_receive_id_read,
            'user_receive_id' => $user_receive_id
        ]);

    }













    /*--- GET LAST MESSAGE INSERTED ---*/

    public function getLastMessageInserted($user_id, $user_id_offer, $product_id, $created_at, $offer_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                message.id AS message_id,
                message.user_id AS message_user_id,
                message.offer_id AS message_offer_id,
                message.product_id AS message_product_id,
                message.message AS message_message,
                message.created_at AS message_created_at,
                message.updated_at AS message_updated_at,
                message.user_offer_id AS message_user_offer_id,
                message.user_send_id_read AS message_user_send_id_read,
                message.user_receive_id_read AS message_user_receive_id_read,
                message.user_receive_id AS message_user_receive_id
                FROM
                message
                WHERE message.user_id = :user_id AND
                      message.offer_id = :offer_id AND
                      message.product_id = :product_id AND
                      message.created_at = :created_at AND
                      message.user_offer_id = :user_offer_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id,
            'offer_id' => $offer_id,
            'product_id' => $product_id,
            'created_at' => $created_at,
            'user_offer_id' => $user_id_offer
        ]);

        return $result->fetchAllAssociative();
    }













    /*--- GET MESSAGE BY OFFER ORDER BY MESSAGE ASC ---*/

    public function getMessageByOffer($offer_id, $user_id, $user_offer_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                message.id AS message_id,
                message.user_id AS message_user_id,
                message.offer_id AS message_offer_id,
                message.product_id AS message_product_id,
                message.message AS message_message,
                message.created_at AS message_created_at,
                message.updated_at AS message_updated_at,
                message.user_offer_id AS message_user_offer_id,
                message.user_send_id_read AS message_user_send_id_read,
                message.user_receive_id_read AS message_user_receive_id_read,
                message.user_receive_id AS message_user_receive_id,
                
                user.name AS user_message_name,
                user.firstname AS user_message_firstname,
                user.email AS user_message_email,
                user.phone AS user_message_phone
            
                FROM message
                
                INNER JOIN user ON user.id = message.user_id

                WHERE message.offer_id = :offer_id AND
                      (message.user_id = :user_id OR message.user_id = :user_offer_id)
                      /*message.user_offer_id = :user_offer_id*/
                
                ORDER BY message.created_at ASC';




        $result = $conn->executeQuery($sql, [
            'offer_id' => $offer_id,
            'user_id' => $user_id,
            'user_offer_id' => $user_offer_id
        ]);

        return $result->fetchAllAssociative();
    }







    /*--- DELETE MESSAGE BY PRODUCT_ID ---*/

    public function deleteMessageByProductId($product_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM message
                WHERE product_id = :product_id';

        $result = $conn->executeQuery($sql, [
            'product_id' => $product_id
        ]);
    }





    /*--- DELETE MESSAGE BY USER_ID ---*/

    public function deleteMessageByUserId($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'DELETE
                FROM message
                WHERE 
                    user_id = :user_id OR
                    user_receive_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);
    }


















    /*--- UPDATE USER RECEIVE ID READ (OFFER_ID + USER_ID) ---*/

    public function updateMessageReceiveRead($offer_id, $user_receive_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE
                message
                SET user_receive_id_read = "true"
                
                WHERE
                
                offer_id = :offer_id
                
                AND
                
                user_receive_id = :user_receive_id';

        $result = $conn->executeQuery($sql, [
            'offer_id' => $offer_id,
            'user_receive_id' => $user_receive_id
        ]);
    }









    /*--- FIND MESSAGE BY OFFER_ID USER_ID ---*/

    public function findMessageByOfferIdUserId($offer_id, $user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                    message.id AS message_id,
                    message.user_id AS message_user_id,
                    message.offer_id AS message_offer_id,
                    message.product_id AS message_product_id,
                    message.message AS message_message,
                    message.created_at AS message_created_at,
                    message.updated_at AS message_updated_at,
                    message.user_offer_id AS message_user_offer_id,
                    message.user_send_id_read AS message_user_send_id_read,
                    message.user_receive_id_read AS message_user_receive_id_read,
                    message.user_receive_id AS message_user_receive_id
                FROM
                message
                
                WHERE
                
                    message.offer_id = :offer_id
                
                AND

                    message.user_receive_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'offer_id' => $offer_id,
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }














    /*--- FIND ALL RECEIVE MESSAGES BY USER_ID ---*/

    public function findReceiveMessageByUserId($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT

                    message.id AS message_id,
                    message.user_id AS message_user_id,
                    message.offer_id AS message_offer_id,
                    message.product_id AS message_product_id,
                    message.message AS message_message,
                    message.created_at AS message_created_at,
                    message.updated_at AS message_updated_at,
                    message.user_offer_id AS message_user_offer_id,
                    message.user_send_id_read AS message_user_send_id_read,
                    message.user_receive_id_read AS message_user_receive_id_read,
                    message.user_receive_id AS message_user_receive_id

                FROM

                message

                WHERE
                
                    message.user_receive_id = :user_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }



    //    /**
    //     * @return Message[] Returns an array of Message objects
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

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
