<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

use Cassandra\Date;
use PDO;

/**
 *
 */
class AdsManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'ads';

    const FK_CATEGORY = 'categories';
    const FK_LOCALISATION = 'localisation';
    const FK_USERS = 'users';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $ads
     * @return int
     */
    public function insert(array $ads): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE .
            " (`title`, `description`, `date`, `categories_id`, `localisation_id`, `users_id`) " .
            " VALUES (:title, :description, :date, :categories_id, :localisation_id, :users_id  )"
        );


        $statement->bindValue('title', $ads['title'], PDO::PARAM_STR);
        $statement->bindValue('description', $ads['description'], PDO::PARAM_STR);
        $statement->bindValue('date', $ads['date'], PDO::PARAM_STR);
//        $statement->bindValue('archives_id', $ads['archives_id'], PDO::PARAM_INT);
        $statement->bindValue('categories_id', $ads['categories_id'], PDO::PARAM_INT);
        $statement->bindValue('localisation_id', $ads['localisation_id'], PDO::PARAM_INT);
        $statement->bindValue('users_id', $ads['users_id'], PDO::PARAM_INT);


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
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param array $ads
     * @return bool
     */
    public function update(array $ads): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE .
            " SET `title` = :title, `description` = :description WHERE id=:id"
        );
        $statement->bindValue('id', $ads['id'], PDO::PARAM_INT);
        $statement->bindValue('title', $ads['title'], PDO::PARAM_STR);
        $statement->bindValue('description', $ads['description'], PDO::PARAM_STR);

        return $statement->execute();
    }
}
