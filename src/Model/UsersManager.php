<?php


namespace App\Model;

use App\Model\Connection;
use PDO;

class UsersManager extends AbstractManager
{
    const TABLE = 'users';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $users
     * @return int
     */
    public function insert(array $users): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE .
            " (`firstname`,`lastname`,`email`,`password`,`admin`)"
            . "VALUES (:firstname, :lastname, :email, :password, :admin)"
        );
        $statement->bindValue('firstname', $users['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $users['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('email', $users['email'], \PDO::PARAM_STR);
        $statement->bindValue('password', $users['password'], \PDO::PARAM_STR);
        $statement->bindValue('admin', 0, \PDO::PARAM_BOOL);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param array $users
     * @return bool
     */
    public function update(array $users): bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
            " SET firstname=:firstname, lastname=:lastname, email=:email, phone=:phone," .
            " address=:address, postcode=:postcode, city=:city, password=:password, image=:image WHERE id=:id");
        $statement->bindValue('id', $users['id'], PDO::PARAM_INT);
        $statement->bindValue('firstname', $users['firstname'], PDO::PARAM_STR);
        $statement->bindValue('lastname', $users['lastname'], PDO::PARAM_STR);
        $statement->bindValue('email', $users['email'], PDO::PARAM_STR);
        $statement->bindValue('phone', $users['phone'], PDO::PARAM_STR);
        $statement->bindValue('address', $users['address'], PDO::PARAM_STR);
        $statement->bindValue('postcode', $users['postcode'], PDO::PARAM_STR);
        $statement->bindValue('city', $users['city'], PDO::PARAM_STR);
        $statement->bindValue('password', $users['password'], PDO::PARAM_STR);
        $statement->bindValue('image', $users['image'], PDO::PARAM_STR);
        $statement->bindValue('password', $users['password'], PDO::PARAM_STR);

        return $statement->execute();
    }



    public function selectAllAndConnect($email, $password)
    {
        //prepared request
        $statement = $this->pdo->prepare(
            "SELECT * FROM $this->table WHERE email =:email AND password = :password"
        );
        $statement->bindValue('email', $email, PDO::PARAM_STR);
        $statement->bindValue('password', $password, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }


    public function getUserRating($userId)
    {
        //prepared request
        $statement = $this->pdo->prepare(
            "SELECT * FROM ratings WHERE target_id =:userId "
        );
        $statement->bindValue('userId', $userId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
