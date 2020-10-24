<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class AjaxController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Usuário não logado']);
            exit;
        }
    }

    public function like($attr) {
        $id = $attr['id'];

        if(PostHandler::isLiked($id, $this->loggedUser->getId())) {
            PostHandler::deleteLike($id, $this->loggedUser->getId());
        } else {
            PostHandler::addLike($id, $this->loggedUser->getId());
        }
    }

}