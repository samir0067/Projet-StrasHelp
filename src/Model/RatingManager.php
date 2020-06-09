<?php


namespace App\Model;

use PDO;

class RatingManager extends AbstractManager
{
    const TABLE = 'ratings';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $guestbook
     * @return int
     */
    public function insert(array $guestbook): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (users_id, target_id, rating) VALUES " .
            "(:users_id, :target_id, :rating)");
        $statement->bindValue('users_id', $guestbook['users_id'], PDO::PARAM_INT);
        $statement->bindValue('target_id', $guestbook['target_id'], PDO::PARAM_INT);
        $statement->bindValue('rating', $guestbook['rating'], PDO::PARAM_INT);


        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }
}
