<?php


namespace App\Model;

class HomeManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'ads';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    public function get3LastAds()
    {
        return $this->pdo->query(
            'SELECT ' . self::TABLE . '.*, categories.image FROM ' . self::TABLE .
            ' JOIN categories ON categories.id=' . self::TABLE .
            ' .categories_id ORDER BY id DESC LIMIT 3'
        )->fetchAll();
    }

    public function searchQuery($query)
    {
        if (empty($query)) {
            return null;
        } else {
            return $this->pdo->query(
                'SELECT *, ads.id AS ad_id, categories.name AS category_name FROM ads 
    INNER JOIN categories ON categories.id = ads.categories_id
    INNER JOIN users ON users.id = ads.users_id
    INNER JOIN localisation ON localisation.id = ads.localisation_id
    WHERE CONCAT(ads.description, ads.title, users.firstname, 
    users.lastname, localisation.postcode, categories.name) LIKE "%' . $query . '%"
    '
            )->fetchAll();
        }
    }
}
