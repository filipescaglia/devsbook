<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class PostController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false)
            $this->redirect('/login');
    }

    public function delete($attr = []) {
        if(!empty($attr['id'])) {
            $idPost = $attr['id'];

            PostHandler::delete(
                $idPost,
                $this->loggedUser->getId()
            );
        }

        $this->redirect('/');
    }

    public function new() {
        $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_SPECIAL_CHARS);

        if($body) {
            PostHandler::addPost(
                $this->loggedUser->getId(),
                'text',
                $body
            );
        }

        $this->redirect('/');
    }

}