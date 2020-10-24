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

    public function comment() {
        $response = ['error' => ''];

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $txt = filter_input(INPUT_POST, 'txt', FILTER_SANITIZE_SPECIAL_CHARS);

        if($id && $txt) {
            PostHandler::addComment($id, $txt, $this->loggedUser->getId());

            $response['link'] = '/profile/' . $this->loggedUser->getId();
            $response['avatar'] = '/media/avatars/' . $this->loggedUser->getAvatar();
            $response['name'] = $this->loggedUser->getName();
            $response['body'] = $txt;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
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