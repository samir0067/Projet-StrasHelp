<?php


namespace App\Controller;

use App\Model\ArchiveManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ArchiveController extends AbstractController
{
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index()
    {
        $archiveManager = new ArchiveManager();

        $categories = $archiveManager->selectAllCategories();
        $postcodes = $archiveManager->selectAllPostcodes();

        $allArchive = "";


        if ((!empty($_POST['category_name'])) && (empty($_POST['postcode']))) {
            $allArchive = $archiveManager->selectAllByCategory($_POST['category_name']);
        } elseif ((empty($_POST['category_name'])) && (!empty($_POST['postcode']))) {
            $allArchive = $archiveManager->selectAllByPostcode($_POST['postcode']);
        } elseif ((!empty($_POST['category_name'])) && (!empty($_POST['postcode']))) {
            $allArchive = $archiveManager->selectAllByFilters($_POST['category_name'], $_POST['postcode']);
        } else {
            $allArchive = $archiveManager->selectAll();
        }

        return $this->twig->render('Archive/index.html.twig', ['allArchive' => $allArchive,
            'categories' => $categories, 'postcodes' => $postcodes]);
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
        $archiveManager = new ArchiveManager();
        $archive = $archiveManager->selectOneById($id);
        return $this->twig->render('Archive/view.html.twig', [
            'archive' => $archive,
        ]);
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        if (empty($_SESSION) || ($_SESSION['admin']) == 0) {
            header('location: /Home/index');
        }
            $archiveManager = new ArchiveManager();
            $archiveManager->delete($id);
            header('Location:/Archive/index');
    }
}
