<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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



    /*--- FIND USER BY ID ---*/

    public function findUserById($user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                *
                FROM user
                WHERE id = :user_id';

        $result = $conn->executeQuery($sql, [
            'user_id' => $user_id
        ]);

        return $result->fetchAllAssociative();
    }









    /*--- FIND USER BY ID => OBJ ---*/

    public function findUserByIdObj($user_id) {

        $qb = $this->createQueryBuilder('p')
              ->where('p.id = :id')
              ->setParameter('id', $user_id);

        $query = $qb->getQuery();

        return $query->execute();
    }








    /*--- FIND USER BY EMAIL ---*/

    public function findUserByEmail($email) {

        $conn= $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                *
                FROM user
                WHERE
                email = :email';

        $result = $conn->executeQuery($sql, [
            'email' => $email
        ]);

        return $result->fetchAllAssociative();
    }






    /*--- REGISTER USER ---*/

    public function registerUser($name, $firstname, $email, $phone, $password, $created_at, $updated_at) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'INSERT INTO
                user
                (name, firstname, email, phone, password, created_at, updated_at)
                VALUES
                (:name, :firstname, :email, :phone, :password, :created_at, :updated_at)';

        $result = $conn->executeQuery($sql, [
            'name' => $name,
            'firstname' => $firstname,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
    }









    /*--- UPDATE USER ---*/

    public function updateUser($name, $firstname, $email, $phone, $password, $updated_at, $user_id) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'UPDATE user
                SET name = :name,
                    firstname = :firstname,
                    email = :email,
                    phone = :phone,
                    password = :password,
                    updated_at = :updated_at
                WHERE id = :user_id';

        $result = $conn->executeQuery($sql, [
            'name' => $name,
            'firstname' => $firstname,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'updated_at' => $updated_at,
            'user_id' => $user_id
        ]);
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
