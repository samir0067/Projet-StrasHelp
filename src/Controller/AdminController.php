<?php


namespace App\Controller;

use App\Model\AdminManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminController extends AbstractController
{

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index()
    {
        if (empty($_SESSION) || $_SESSION['admin'] == 0) {
            header('location: /Home/index');
        }
            $usersManager = new AdminManager();
            $allUsers = $usersManager->selectAll();

            return $this->twig->render('Admin/index.html.twig', ['allUsers' => $allUsers]);
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
        if (empty($_SESSION) || ($_SESSION['admin']) == 0) {
            header('location: /Home/index');
        }
            $usersManager = new AdminManager();
            $users = $usersManager->selectOneById($id);
            $allAds = $usersManager->selectAdsById($id);
            $allMess = $usersManager->selectMessageById($id);
            return $this->twig->render('Admin/view.html.twig', [
                'users' => $users,
                'allads' => $allAds,
                'allMess' => $allMess
            ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function edit(int $id): string
    {
        if (empty($_SESSION) || ($_SESSION['admin']) == 0) {
            header('location: /Home/index');
        }
            $usersManager = new AdminManager();
            $users = $usersManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_SESSION['admin'] == 1) {
                $users['admin'] = $_POST['admin'];
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
        }
            return $this->twig->render('Admin/View/edit.html.twig', ['users' => $users]);
    }

    /**
     * @param int $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function modify(int $id): string
    {
        if (empty($_SESSION) || ($_SESSION['admin']) == 0) {
            header('location: /Home/index');
        }
            $usersManager = new AdminManager();
            $users = $usersManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_SESSION)) {
                if ($_SESSION['admin'] == 1) {
                    $users['admin'] = $_POST['admin'];
                    $users['firstname'] = $_POST['firstname'];
                    $users['lastname'] = $_POST['lastname'];
                    $users['email'] = $_POST['email'];
                    $users['image'] = $_POST['image'];
                }
            }

            $id = $usersManager->smallUpdate($users);
            header('Location:/Admin/index/' . $id);
        }
            return $this->twig->render('Admin/modify.html.twig', [
                'users' => $users
            ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_SESSION)) {
                if ($_SESSION['admin'] == 1) {
                    $usersManager = new AdminManager();
                    $users = [
                        'admin' => $_POST['admin'],
                        'firstname' => $_POST['firstname'],
                        'lastname' => $_POST['lastname'],
                        'email' => $_POST['email'],
                        'password' => $_POST['password'],
                        'image' => $_POST['image'],
                    ];
                    $id = $usersManager->insert($users);
                    header('Location:/Admin/index/' . $id);
                }
            }
        }
        return $this->twig->render('Admin/add.html.twig');
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        if (empty($_SESSION) || ($_SESSION['admin']) == 0) {
            header('location: /Home/index');
        }
            $usersManager = new AdminManager;
            $usersManager->deleteAllAds($id);
            $usersManager->deleteAllMess($id);
            $usersManager->deleteAllGBook($id);
            $usersManager->delete($id);
            header('Location:/Admin/index');
    }

    /**
     * @param int $id
     */
    public function deleteAds(int $id)
    {
        if (empty($_SESSION) || ($_SESSION['admin']) == 0) {
            header('location: /Home/index');
        }
            $usersManager = new AdminManager;
            $usersManager->deleteAds($id);
            header('Location:/Admin/index');
    }

    /**
     * @param int $id
     */
    public function archive(int $id)
    {
        if (empty($_SESSION) || ($_SESSION['admin']) == 0) {
            header('location: /Home/index');
        }
            $archiveManager = new AdminManager;
            $archiveManager->archive((int)$id);
            header('Location:/Archive/index');
    }
}
