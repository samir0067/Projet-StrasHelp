<?php


namespace App\Model;

use PDO;

class ArchiveManager extends AbstractManager
{
    const ARCHTABLE = 'archive';
    const FK_CATEGORY = 'categories';
    const FK_LOCALISATION = 'localisation';
    const FK_USERS = 'users';

    /**
     * ArchiveManager constructor.
     */
    public function __construct()
    {
        parent::__construct(self::ARCHTABLE);
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::ARCHTABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Get all row from database.
     *
     * @return array
     */
    public function selectAll(): array
    {
        return $this->pdo->query(
            'SELECT archive.id, archive.title, archive.brief_desc,  ' .
            'DATE_FORMAT(archive.date, \'%e %M %Y\') AS datePosted, cat.name AS category_name, users.firstname,' .
            ' UPPER(users.lastname) AS lastname, loc.postcode  FROM ' . $this->table
            . ' INNER JOIN localisation loc ON loc.id = archive.localisation_id INNER' .
            ' JOIN categories cat ON cat.id = archive.categories_id INNER JOIN ' .
            'users ON users.id = archive.users_id'
        )->fetchAll();
    }

    public function selectAllByCategory($categoryName): array
    {
        return $this->pdo->query(
            'SELECT archive.id, archive.title, archive.brief_desc,  ' .
            'DATE_FORMAT(archive.date, \'%e %M %Y\') AS datePosted, cat.name AS category_name, users.firstname,' .
            ' UPPER(users.lastname) AS lastname, loc.postcode  FROM ' . $this->table
            . ' INNER JOIN localisation loc ON loc.id = archive.localisation_id INNER' .
            ' JOIN categories cat ON cat.id = archive.categories_id INNER JOIN ' .
            'users ON users.id = archive.users_id ' . ' WHERE cat.name = "' . $categoryName.'"'
        )->fetchAll();
    }

    public function selectAllByPostcode($postcode): array
    {
        return $this->pdo->query(
            'SELECT archive.id, archive.title, archive.brief_desc,  ' .
            'DATE_FORMAT(archive.date, \'%e %M %Y\') AS datePosted, cat.name AS category_name, users.firstname,' .
            ' UPPER(users.lastname) AS lastname, loc.postcode  FROM ' . $this->table
            . ' INNER JOIN localisation loc ON loc.id = archive.localisation_id INNER' .
            ' JOIN categories cat ON cat.id = archive.categories_id INNER JOIN ' .
            'users ON users.id = archive.users_id ' . ' WHERE loc.postcode = "' . $postcode.'"'
        )->fetchAll();
    }


    public function selectAllByFilters($categoryName, $postcode): array
    {
        return $this->pdo->query(
            'SELECT archive.id, archive.title, archive.brief_desc,  ' .
            'DATE_FORMAT(archive.date, \'%e %M %Y\') AS datePosted, cat.name AS category_name, users.firstname,' .
            ' UPPER(users.lastname) AS lastname, loc.postcode  FROM ' . $this->table
            . ' INNER JOIN localisation loc ON loc.id = archive.localisation_id INNER' .
            ' JOIN categories cat ON cat.id = archive.categories_id INNER JOIN ' .
            'users ON users.id = archive.users_id ' . ' WHERE cat.name = "' . $categoryName.'"'
            . ' AND loc.postcode = "' . $postcode .'"'
        )->fetchAll();
    }


    public function selectOneById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare(
            'SELECT users.firstname, users.lastname, archive.id, archive.title, archive.description, users.email, '
            . 'archive.brief_desc, DATE_FORMAT(archive.date, \'%e %M %Y\') AS datePosted, users.firstname,' .
            ' UPPER(users.lastname) AS lastname, loc.postcode FROM ' .
            $this->table . ' INNER JOIN localisation loc ON loc.id = archive.localisation_id ' .
            'INNER JOIN categories cat ON cat.id = archive.categories_id INNER JOIN users ' .
            'ON users.id = archive.users_id WHERE archive.id=:id'
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectAllPostcodes(): array
    {
        return $this->pdo->query('SELECT * FROM localisation')->fetchAll();
    }

    public function selectAllCategories(): array
    {
        return $this->pdo->query('SELECT * FROM categories')->fetchAll();
    }
}
