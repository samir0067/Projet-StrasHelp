<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\AdsManager;
use App\Model\AdsSelection;
use App\Model\HomeManager;
use App\Model\MessagesManager;
use App\Model\UsersManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends AbstractController
{
    public function index()
    {
        $homeContent = new HomeManager();
        $last3Ads = $homeContent->get3LastAds();
        $messagesManager = new MessagesManager();

        $adsSelector = new AdsSelection();
        $usersManager = new UsersManager();
        $allAds = $adsSelector->selectAll();
        $allUsers = $usersManager->selectAll();
        $allCategories = $adsSelector->selectAllCategories();
        $allPostcodes = $adsSelector->selectAllPostcodes();
        $last5Messages = $messagesManager->get5LastMessages();

        $this->twig->render(
            'NavFoot/navbar.html.twig',
            [
            'ads' => $allAds,
            'users' => $allUsers,
            'categories' => $allCategories,
            'postcodes' => $allPostcodes,

            ]
        );

        return $this->twig->render(
            'Home/index.html.twig',
            [
            'last5Messages' => $last5Messages,
            'last3Ads' => $last3Ads,
            'ads' => $allAds,
            'users' => $allUsers,
            'categories' => $allCategories,
            'postcodes' => $allPostcodes,

            ]
        );
    }

    public function carousel()
    {
        $homeContent = new HomeManager();
        $last3Ads = $homeContent->get3LastAds();
        $messagesManager = new MessagesManager();
        $last5Messages = $messagesManager->get5LastMessages();
        return $this->twig->render(
            'Home/carousel.html.twig',
            [
                'last5Messages' => $last5Messages,
                'last3Ads' => $last3Ads]
        );
    }

    public function search()
    {
        $query = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $query = $_POST['query'];//explode(' ', $_POST['query']);
        }


        $homeContent = new HomeManager();

        $allAds = $homeContent->searchQuery($query);
        return $this->twig->render(
            'Home/search.html.twig',
            [
                'ads' => $allAds,
                'query' => $query,
            ]
        );
    }

    public function condGen()
    {
        return $this->twig->render('CondGen/cg.html.twig');
    }

    public function viePrive()
    {
        return $this->twig->render('CondGen/vp.html.twig');
    }
}
