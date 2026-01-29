<?php

session_start();

//gérer les routes

include '../vendor/autoload.php';
//Import des ressources
use Dotenv\Dotenv;
use Mithridatem\Routing\Route;
use Mithridatem\Routing\Router;
use Mithridatem\Routing\Exception\RouteNotFoundException;
use Mithridatem\Routing\Exception\UnauthorizedException;

//Import du fichier .env
$dotenv = Dotenv::createImmutable("../");
$dotenv->load();

/* //Analyse de l'URL avec parse_url() et retourne ses composants
$url = parse_url($_SERVER['REQUEST_URI']);
//test soit l'url a une route sinon on renvoi à la racine
$path = isset($url['path']) ? $url['path'] : '/';

//Comparer avec la liste d'url :
switch ($path) {
    case '/':
        $homeController->index();
        break;
    case '/login':
        $registerController->login();
        break;
    case '/register':
        $registerController->register();
        break;
    case '/category/add':
        $categoryController->addCategorie();
        break;
    case '/category/all':
        $categoryController->showAllCategories();
        break;
    case '/quizz/add':
        $quizzController->addQuizz();
        break;
    case '/logout':
        $registerController->logout();
        break;
    default:
        echo "erreur 404";
        break;
} */

//Déclarer les routes
$router = new Router();
$router->map(Route::controller('GET', '/', App\Controller\HomeController::class, 'index'));
$router->map(Route::controller('GET', '/test/{nbr}', App\Controller\HomeController::class, 'test'));
$router->map(Route::controller('GET', '/login', App\Controller\RegisterController::class, 'login'));
$router->map(Route::controller('POST', '/login', App\Controller\RegisterController::class, 'login'));
$router->map(Route::controller('GET', '/register', App\Controller\RegisterController::class, 'register'));
$router->map(Route::controller('POST', '/register', App\Controller\RegisterController::class, 'register'));
$router->map(Route::controller('GET', '/category/add', App\Controller\CategoryController::class, 'addCategorie'));
$router->map(Route::controller('POST', '/category/add', App\Controller\CategoryController::class, 'addCategorie'));
$router->map(Route::controller('GET', '/category/all', App\Controller\CategoryController::class, 'showAllCategories'));
$router->map(Route::controller('GET', '/quizz/add', App\Controller\QuizzController::class, 'addQuizz'));
$router->map(Route::controller('POST', '/quizz/add', App\Controller\QuizzController::class, 'addQuizz'));
$router->map(Route::controller('GET', '/logout', App\Controller\RegisterController::class, 'logout'));
$router->map(Route::controller('GET', '/upload', App\Controller\HomeController::class, 'testUpload'));
$router->map(Route::controller('POST', '/upload', App\Controller\HomeController::class, 'testUpload'));
$router->map(Route::controller('GET', '/quizz/id/{id}', App\Controller\QuizzController::class, 'showQuizz'));
$router->map(Route::controller('GET', '/me', App\Controller\ProfileController::class, 'profile'));

try  {
    $router->dispatch();
} catch(RouteNotFoundException $re) {
    echo $re->getMessage();
} catch(UnauthorizedException $ue) {
    echo $ue->getMessage();
}
