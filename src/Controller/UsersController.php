<?php

namespace App\Controller;

use App\Model\AdminManager;
use App\Model\ItemManager;
use App\Model\RatingManager;
use App\Model\UsersManager;
use http\Header;
use Nette\DI\Definitions\LocatorDefinition;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UsersController extends AbstractController
{


    public function index()
    {
        if (empty($_SESSION['id'])) {
            header('location: /users/sign');
        }
        $usersManager = new UsersManager();
        $allUsers = $usersManager->selectAll();
        return $this->twig->render('users/index.html.twig', ['allUsers' => $allUsers]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usersManager = new UsersManager();
            $user = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
            ];
            $id = $usersManager->insert($user);
            header('Location:/users/sign/' . $id);
        }

        return $this->twig->render('users/add.html.twig');
    }

    /**
     * Display item informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function show(int $id)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_SESSION['id'])) {
            if (isset($_POST['rating'])) {
                $itemManager = new RatingManager();
                $item = [
                    'rating' => intval($_POST['rating']),
                    'target_id'=>$id,
                    'users_id' => $_SESSION['id']
                ];
                $itemManager->insert($item);
                unset($_POST['rating']);
            }
        }


        $usersManager = new UsersManager();
        $user = $usersManager->selectOneById($id);
        $rating = $usersManager->getUserRating($id);

        $ratingCount = count($rating);
        $avgRating = 0;
        foreach ($rating as $v) {
            $avgRating += intval($v["rating"]);
        }
        $avgRating = count($rating) ?
            round($avgRating / count($rating), 2) : null;
        $usersManager = new AdminManager();
        $users = $usersManager->selectOneById($id);
        $allAds = $usersManager->selectAdsById($id);
        $allMess = $usersManager->selectMessageById($id);
        return $this->twig->render(
            'users/show.html.twig',
            [
            'users' => $users,
            'allads' => $allAds,
            'allMess' => $allMess,
            'rating' => $avgRating,
                'ratingCount'=>$ratingCount
            ]
        );

        return $this->twig->render('users/show.html.twig', ['users' => $user]);
    }

    /**
     * Display item edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function edit(int $id): string
    {
        $usersManager = new UsersManager();
        $users = $usersManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $users['firstname'] = $_POST['firstname'];
            $users['lastname'] = $_POST['lastname'];
            $users['email'] = $_POST['email'];
            $users['phone'] = $_POST['phone'];
            $users['address'] = $_POST['address'];
            $users['postcode'] = $_POST['postcode'];
            $users['city'] = $_POST['city'];
            $users['password'] = $_POST['password'];
            $users['image'] = $_POST['image'];
            $usersManager->update($users);
        }
        return $this->twig->render('users/edit.html.twig', ['users' => $users]);
    }

    /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $usersManager = new UsersManager();
        $usersManager->delete($id);
        header('Location:/users/index');
    }

    public function sign()
    {
        if (!empty($_SESSION['firstname'])) {
            $this->alertUser(['Bienvenue', $_SESSION['firstname'], $_SESSION['lastname']]);
            header('Location: /Home/index');
        }
        //On ouvre une session non identifiée

        //Si la form vient d'être remplie et que l'user vient de se connecter
        // on récupère les données de la base dans des variables qu'on stocke dans un cookie

        $userManager = new UsersManager();
        $allUsersToConnect = "";


        if ((!empty($_POST['emailLog'])) && (!empty($_POST['passwordLog']))) {
            $userEMail = $_POST['emailLog'];
            $userPassword = $_POST['passwordLog'];

            unset($_POST['emailLog']);
            unset($_POST['passwordLog']);

            $allUsersToConnect = $userManager->selectAllAndConnect($userEMail, $userPassword);


            if (empty($allUsersToConnect)) {
                $this->alertUser(['Mauvais identifiant.']);
            }
            if (!empty($allUsersToConnect)) {
                $_SESSION['firstname'] = $allUsersToConnect['firstname'];
                $_SESSION['lastname'] = $allUsersToConnect['lastname'];
                $_SESSION['isOnline'] = 'Connecté';
                $_SESSION['id'] = $allUsersToConnect['id'];
                $_SESSION['admin'] = $allUsersToConnect['admin'];
            }

            if (!empty($_SESSION['firstname'])) {
                $this->alertUser(['Bienvenue', $_SESSION['firstname'], $_SESSION['lastname']]);
                header('Location: /Home/index');
            }
        }
        return $this->twig->render('users/sign.html.twig');
    }


    /**
     * @param array $array
     */
    public function alertUser(array $array)
    {
        $phrase = "";
        foreach ($array as $val) {
            $phrase .= $val . " ";
        }
        echo "<script>alert('" . $phrase . "')</script>";
    }

    /**
     * @param int $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function view(int $id)
    {
        $usersManager = new AdminManager();
        $users = $usersManager->selectOneById($id);
        $allAds = $usersManager->selectAdsById($id);
        $allMess = $usersManager->selectMessageById($id);
        return $this->twig->render(
            'users/show.html.twig',
            [
            'users' => $users,
            'allads' => $allAds,
            'allMess' => $allMess
            ]
        );
    }
}
