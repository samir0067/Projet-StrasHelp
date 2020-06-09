<?php

namespace App\Model;

use PDO;

class MessagesManager extends AbstractManager
{
    const TABLE = 'messages';



    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $messages
     * @return int
     */
    public function insert(array $messages): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (name, content) VALUES " .
            "(:name, :content)");
        $statement->bindValue('name', $messages['name'], PDO::PARAM_STR);
        $statement->bindValue('message', $messages['message'], PDO::PARAM_STR);


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
        $statement = $this->pdo->prepare("SELECT * FROM $this->table"
            . " JOIN messages ON users.id=messages.users_id WHERE users.id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function get5LastMessages()
    {
        $statement = $this->pdo->prepare("SELECT messages.*,users.firstname,users.lastname FROM $this->table"
            . " JOIN users ON users.id=messages.users_id LIMIT 5");
        $statement->execute();

        return $statement->fetchAll();
    }
}
