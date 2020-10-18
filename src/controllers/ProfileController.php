<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class ProfileController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false)
            $this->redirect('/login');
    }

    public function index($attr = []) {
        $id = $this->loggedUser->getId();
        $page = intval(filter_input(INPUT_GET, 'page'));

        if(!empty($attr['id'])) {
            $id = $attr['id'];
        }

        $user = UserHandler::getUser($id, true);

        if(!$user) {
            $this->redirect('/');
        }

        $dateFrom = new \DateTime($user->getBirthdate());
        $dateTo = new \DateTime('today');        
        $user->setAge($dateFrom->diff($dateTo)->y);

        $feed = PostHandler::getUserFeed(
            $id,
            $page,
            $this->loggedUser->getId()
        );

        $this->render('profile', [
            'feed' => $feed,
            'loggedUser' => $this->loggedUser,
            'user' => $user
        ]);
    }

}