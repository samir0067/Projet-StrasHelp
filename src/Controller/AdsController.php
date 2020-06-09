<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\AdsManager;
use App\Model\AdsSelection;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class ItemController
 *
 */
class AdsController extends AbstractController
{
    /**
     * Display item listing
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index()
    {
        $adsManager = new AdsSelection();

        $categories = $adsManager->selectAllCategories();
        $postcodes = $adsManager->selectAllPostcodes();


        $allAds = "";

        $searchFilters = $_POST;

        if ((!empty($_POST['category_name'])) && (empty($_POST['postcode']))) {
            $allAds = $adsManager->selectAllByCategory($_POST['category_name']);
        } elseif ((empty($_POST['category_name'])) && (!empty($_POST['postcode']))) {
            $allAds = $adsManager->selectAllByPostcode($_POST['postcode']);
        } elseif ((!empty($_POST['category_name'])) && (!empty($_POST['postcode']))) {
            $allAds = $adsManager->selectAllByFilters($_POST['category_name'], $_POST['postcode']);
        } else {
            $allAds = $adsManager->selectAll();
        }

        return $this->twig->render(
            'Ads/index.html.twig',
            ['allAds' => $allAds,
                'categories' => $categories,
                'postcodes' => $postcodes,
                'searchFilters'=>$searchFilters
            ]
        );
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
        if (empty($_SESSION['id'])) {
            header('location: /users/sign');
        }
        $adsSelector = new AdsSelection();
        $adInfo = $adsSelector->selectOneById($id);
        $categories = $adsSelector->selectAllCategories();
        $postcodes = $adsSelector->selectAllPostcodes();
        $sessionDetails = $_SESSION;

        return $this->twig->render(
            'Ads/show.html.twig',
            ['adInfo' => $adInfo, 'categorie' => $categories, 'postcode' => $postcodes, 'session'=>$sessionDetails]
        );
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
        $adsManager = new AdsManager();
        $adsSelector = new AdsSelection();
        $categories = $adsSelector->selectAllCategories();
        $postcodes = $adsSelector->selectAllPostcodes();
        $adInfo = $adsSelector->selectOneById($id);
        $sessionDetails = $_SESSION;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adInfo['firstname'] = $adInfo['firstname'];
            $adInfo['lastname'] = $adInfo['lastname'];
            $adInfo['title'] = $_POST['title'];
            $adInfo['description'] = $_POST['description'];
            $adInfo['categories_id'] = $_POST['categories_id'];
            $adInfo['localisation_id'] = $_POST['localisation_id'];


            $adsManager->update($adInfo);
        }

        return $this->twig->render(
            'Ads/edit.html.twig',
            [
                'adInfo' => $adInfo,
                'categorie' => $categories,
                'postcode' => $postcodes,
                'sessionDetails' => $sessionDetails,
            ]
        );
    }


    /**
     * Display item creation page
     *
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

        $adsManager = new AdsManager();
        $adsSelector = new AdsSelection();
        $dateOfAd = date("Y-m-d h:i:s");
        $sessionDetails = $_SESSION;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adInfo = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'categories_id' => $_POST['categories_id'],
                'localisation_id' => $_POST['localisation_id'],
                'date' => $dateOfAd,
//                'archives_id' => '0',
                'users_id' => $_SESSION['id'],
            ];


            $id = $adsManager->insert($adInfo);
            header('Location:/ads/show/' . $id);
        }

        $categories = $adsSelector->selectAllCategories();
        $postcodes = $adsSelector->selectAllPostcodes();

        return $this->twig->render(
            'Ads/add.html.twig',
            [
                'postcode' => $postcodes,
                'categorie' => $categories,
                'sessionDetails' => $sessionDetails,
            ]
        );
    }


    /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $adsManager = new AdsManager();
        $adsManager->delete($id);
        header('Location:/ads/index');
    }
}
