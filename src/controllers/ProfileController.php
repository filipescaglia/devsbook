<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;
use src\models\User_Relation;

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

        $isFollowing = false;
        if($user->getId() != $this->loggedUser->getId()) {
            $isFollowing = UserHandler::isFollowing(
                $this->loggedUser->getId(),
                $user->getId()
            );
        }

        $this->render('profile', [
            'feed' => $feed,
            'isFollowing' => $isFollowing,
            'loggedUser' => $this->loggedUser,
            'user' => $user
        ]);
    }

    public function follow($attr) {
        $to = intval($attr['id']);

        if(UserHandler::idExists($to)) {
            if(UserHandler::isFollowing($this->loggedUser->getId(), $to)) {
                UserHandler::unfollow($this->loggedUser->getId(), $to);
            } else {
                UserHandler::follow($this->loggedUser->getId(), $to);
            }
        }

        $this->redirect("/profile/$to");
    }

    public function friends($attr = []) {
        $id = $this->loggedUser->getId();

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

        $isFollowing = false;
        if($user->getId() != $this->loggedUser->getId()) {
            $isFollowing = UserHandler::isFollowing(
                $this->loggedUser->getId(),
                $user->getId()
            );
        }

        $this->render('profile_friends', [
            'isFollowing' => $isFollowing,
            'loggedUser' => $this->loggedUser,
            'user' => $user
        ]);
    }

}