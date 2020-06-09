<?php

namespace App\Controller;

use App\Model\MessagesManager;

class MessagesController extends AbstractController
{
    public function index()
    {
        $messagesManager = new MessagesManager();
        $last5Messages = $messagesManager->get5LastMessages();
        var_dump($last5Messages);
        return $this->twig->render('Home/index.html.twig', ['last5Messages' => $last5Messages]);
    }


    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messagesManager = new MessagesManager();
            $newMessage = [
                'users_id' => $_POST['name'],
                'messages' => $_POST['content'],
            ];
            $id = $messagesManager->insert($newMessage);
            header('Location:/Home/show/' . $id);
        }
        return $this->twig->render('Home/add.html.twig');
    }

    public function view(int $id)
    {
        $messageManager = new MessagesManager();
        $messageManager = $messageManager->selectMessageById($id);
        return $this->twig->render('Home/show.html.twig', [
            'messageManager'   => $messageManager
        ]);
    }
}
