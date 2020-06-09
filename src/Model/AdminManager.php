<?php


namespace App\Model;

use PDO;

/**
 * Class AdminManager
 * @package App\Model
 * @SuppressWarnings(PHPMD);
 */
class AdminManager extends AbstractManager
{
    const TABLE = 'users';
    const ARCHTABLE = 'archive';
    const ADSTABLE = 'ads';
    const MESSTABLE = 'messages';
    const BOOKTABLE = 'guestbook';

    /**
     * AdminManager constructor.
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
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (admin, firstname, lastname, email, password, image) VALUES " .
            "(:admin, :firstname, :lastname, :email, :password, :image)");
        $statement->bindValue('admin', $users['admin'], PDO::PARAM_INT);
        $statement->bindValue('firstname', $users['firstname'], PDO::PARAM_STR);
        $statement->bindValue('lastname', $users['lastname'], PDO::PARAM_STR);
        $statement->bindValue('email', $users['email'], PDO::PARAM_STR);
        $statement->bindValue('password', $users['password'], PDO::PARAM_STR);
        $statement->bindValue('image', $users['image'], PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param int $id
     */
    public function deleteAds(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::ADSTABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param int $id
     */
    public function deleteAllAds(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::ADSTABLE . " WHERE users_id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param int $id
     */
    public function deleteAllMess(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::MESSTABLE . " WHERE users_id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param int $id
     */
    public function deleteAllGBook(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::BOOKTABLE . " WHERE users_id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param array $users
     * @return bool
     */
    public function update(array $users)
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
            " SET admin=:admin, firstname=:firstname, lastname=:lastname, email=:email, phone=:phone," .
            " address=:address, postcode=:postcode, city=:city, password=:password, image=:image WHERE id=:id");
        $statement->bindValue('id', $users['id'], PDO::PARAM_INT);
        $statement->bindValue('admin', $users['admin'], PDO::PARAM_INT);
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

    /**
     * @param array $users
     * @return bool
     */
    public function smallUpdate(array $users): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
            " SET admin=:admin,firstname=:firstname,lastname=:lastname,email=:email,password=:password,".
            "image=:image WHERE id=:id");
        $statement->bindValue('id', $users['id'], PDO::PARAM_INT);
        $statement->bindValue('admin', $users['admin'], PDO::PARAM_INT);
        $statement->bindValue('firstname', $users['firstname'], PDO::PARAM_STR);
        $statement->bindValue('lastname', $users['lastname'], PDO::PARAM_STR);
        $statement->bindValue('email', $users['email'], PDO::PARAM_STR);
        $statement->bindValue('image', $users['image'], PDO::PARAM_STR);
        $statement->bindValue('password', $users['password'], PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * @param int $id
     * @return array
     */
    public function selectAdsById(int $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table"
            . " JOIN ads ON users.id=ads.users_id WHERE users.id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @param int $id
     * @return array
     */
    public function selectMessageById(int $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table"
            . " JOIN messages ON users.id=messages.users_id WHERE users.id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @param int $id
     */
    public function archive(int $id)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::ARCHTABLE .
            " SELECT * FROM " . self::ADSTABLE . " WHERE id =:id ; " .
            " DELETE FROM " . self::ADSTABLE . " WHERE id =:id ");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
}
