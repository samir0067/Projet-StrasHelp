<?php

namespace App\Model;

use PDO;

class GuestBookManager extends AbstractManager
{
    const TABLE = 'guestbook';

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
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (users_id, message) VALUES " .
            "(:users_id, :message)");
        $statement->bindValue('users_id', $guestbook['users_id'], PDO::PARAM_STR);
        $statement->bindValue('message', $guestbook['message'], PDO::PARAM_STR);


        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function selectMessageById(int $id)
    {
        $statement = $this->pdo->prepare("SELECT guestbook.*,users.firstname,users.lastname FROM $this->table"
            . " JOIN users ON users.id=guestbook.users_id WHERE guestbook.id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }


    public function selectAllMessagesWithUsers()
    {
        $statement = $this->pdo->prepare("SELECT guestbook.*,users.firstname,users.lastname FROM $this->table"
            . " JOIN users ON users.id=guestbook.users_id");
        $statement->execute();

        return $statement->fetchAll();
    }


    /**
     * @param array $item
     * @return bool
     */
    public function update(array $item):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `message` = :content WHERE id=:id");
        $statement->bindValue('id', $item['id'], PDO::PARAM_INT);
        $statement->bindValue('content', $item['content'], PDO::PARAM_STR);

        return $statement->execute();
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
}
