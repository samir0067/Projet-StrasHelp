<?php


namespace App\Controller;

use App\Model\RatingManager;

class RatingController
{
    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_SESSION['id'])) {
            $itemManager = new RatingManager();
            $item = [
                'rating' => intval($_POST['rating']),
                'target_id' => intval($_POST['target']),
                'users_id' => intval($_SESSION['id'])
            ];
            $id = $itemManager->insert($item);
            var_dump($id);
            echo json_encode([]);
        }
    }
}
