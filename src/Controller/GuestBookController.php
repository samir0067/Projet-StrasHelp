<?php

namespace App\Controller;

use App\Model\GuestBookManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GuestBookController extends AbstractController
{
    public function index()
    {
        $guestBookManager = new GuestBookManager();
        $allGuestBook = $guestBookManager->selectAllMessagesWithUsers();

        return $this->twig->render('GuestBook/index.html.twig', ['allGuestBook' => $allGuestBook]);
    }


    /**
     *
     * @param int $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function show(int $id)
    {
        $guestBookManager = new GuestBookManager();
        $allGuestBook = $guestBookManager->selectMessageById($id);
        return $this->twig->render('GuestBook/show.html.twig', ['allGuestBook' => $allGuestBook]);
    }


    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function add()
    {
        if (empty($_SESSION['id'])) {
            header('location: /users/sign');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_SESSION['id'])) {
            $itemManager = new GuestBookManager();
            $item = [
                'message' => $_POST['content'],
                'users_id' => intval($_SESSION['id'])
            ];
            $id = $itemManager->insert($item);
            header('Location:/GuestBook/show/' . $id);
        }

        return $this->twig->render('GuestBook/add.html.twig');
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
        $itemManager = new GuestBookManager();
        $item = $itemManager->selectMessageById($id);
        if ($item[0]['users_id']!=$_SESSION['id']) {
            header("location: /Home/index");
        }
        $data = ["content"=>$item[0]["message"]];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['content'] = $_POST['content'];
            $data['id'] = $id;
            $itemManager->update($data);
        }
        return $this->twig->render('GuestBook/edit.html.twig', ['guestBook' => $data]);
    }

    /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $itemManager = new GuestBookManager();
        $item = $itemManager->selectMessageById($id);
        if ($item[0]['users_id']!=$_SESSION['id']) {
            header("location: /Home/index");
        }
        $itemManager = new GuestBookManager();
        $itemManager->delete($id);
        header('Location:/GuestBook/index');
    }
}
