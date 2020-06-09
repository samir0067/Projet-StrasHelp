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
class AdsSelection extends AbstractManager
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
     * Get all row from database.
     *
     * @return array
     */
    public function selectAll(): array
    {
        return $this->pdo->query(
            'SELECT ads.id, ads.title,  ' .
            'DATE_FORMAT(ads.date, \'%e/%m/%Y\') AS date, ads.description,'
            .' cat.name AS category_name, users.firstname, users.id as user_id, ' .
            ' UPPER(users.lastname) AS lastname, loc.postcode  FROM ' . $this->table
            . ' INNER JOIN localisation loc ON loc.id = ads.localisation_id INNER' .
            ' JOIN categories cat ON cat.id = ads.categories_id INNER JOIN ' .
            'users ON users.id = ads.users_id ORDER BY id DESC'
        )->fetchAll();
    }

    public function selectAllByCategory($categoryName): array
    {
        return $this->pdo->query(
            'SELECT ads.id, ads.title,  ads.description,  ' .
            'DATE_FORMAT(ads.date, \'%e %M %Y\') AS date, cat.name AS category_name, users.id as user_id, '.
            ' users.firstname, UPPER(users.lastname) AS lastname, loc.postcode  FROM ' . $this->table
            . ' INNER JOIN localisation loc ON loc.id = ads.localisation_id INNER' .
            ' JOIN categories cat ON cat.id = ads.categories_id INNER JOIN ' .
            'users ON users.id = ads.users_id ' . ' WHERE cat.name = "' . $categoryName.'" ORDER BY id DESC'
        )->fetchAll();
    }

    public function selectAllByPostcode($postcode): array
    {
        return $this->pdo->query(
            'SELECT ads.id, ads.title, ads.description,  ' .
            'DATE_FORMAT(ads.date, \'%e %M %Y\') AS date, cat.name AS category_name, users.firstname,' .
            ' UPPER(users.lastname) AS lastname, loc.postcode, users.id as user_id  FROM ' . $this->table
            . ' INNER JOIN localisation loc ON loc.id = ads.localisation_id INNER' .
            ' JOIN categories cat ON cat.id = ads.categories_id INNER JOIN ' .
            'users ON users.id = ads.users_id ' . ' WHERE loc.postcode = "' . $postcode.'" ORDER BY id DESC'
        )->fetchAll();
    }


    public function selectAllByFilters($categoryName, $postcode): array
    {
        return $this->pdo->query(
            'SELECT ads.id, ads.title, ads.description,  ' .
            'DATE_FORMAT(ads.date, \'%e %M %Y\') AS date, cat.name AS category_name,'
            .' users.firstname, UPPER(users.lastname) AS lastname, loc.postcode, users.id as user_id  FROM '
            . $this->table . ' INNER JOIN localisation loc ON loc.id = ads.localisation_id INNER' .
            ' JOIN categories cat ON cat.id = ads.categories_id INNER JOIN ' .
            'users ON users.id = ads.users_id ' . ' WHERE cat.name = "' . $categoryName.'"'
            . ' AND loc.postcode = "' . $postcode .'" ORDER BY id DESC'
        )->fetchAll();
    }


    public function selectOneById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare(
            'SELECT users.firstname, users.lastname, ads.id, ads.title, ads.description, users.email, '
            . ' DATE_FORMAT(ads.date, \'%e %M %Y\') AS date, users.firstname, users.id as user_id,' .
            ' UPPER(users.lastname) AS lastname, loc.postcode, cat.name AS category_name, users.image,
             '.' users.phone FROM ' .
            $this->table . ' INNER JOIN localisation loc ON loc.id = ads.localisation_id ' .
            'INNER JOIN categories cat ON cat.id = ads.categories_id INNER JOIN users ' .
            'ON users.id = ads.users_id WHERE ads.id=:id '
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectAllPostcodes(): array
    {
        return $this->pdo->query('SELECT * FROM localisation ORDER BY localisation.postcode ASC')->fetchAll();
    }

    public function selectAllCategories(): array
    {
        return $this->pdo->query('SELECT * FROM categories ORDER BY categories.name ASC')->fetchAll();
    }
}
