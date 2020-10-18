<?php
namespace src\handlers;

use \src\models\User;
use \src\models\UserRelation;
use \src\handlers\PostHandler;

class UserHandler {

    public static function addUser($name, $email, $password, $birthdate) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0, 9999).time());

        User::insert([
            'birthdate' => $birthdate,
            'email' => $email,
            'name' => $name,
            'password' => $hash,
            'token' => $token
        ])->execute();

        return $token;
    }

    public static function checkLogin() {
        if(!empty($_SESSION['token'])) {

            $token = $_SESSION['token'];

            $data = User::select()->where('token', $token)->one();
            if(count($data) > 0) {

                $loggedUser = new User();
                $loggedUser->setId($data['id']);
                //$loggedUser->setEmail($data['email']);
                $loggedUser->setName($data['name']);
                $loggedUser->setAvatar($data['avatar']);
                //$loggedUser->setBirthdate($data['birthdate']);

                return $loggedUser;

            }

        }

        return false;
    }

    public static function emailExists($email) {
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }

    public static function getUser($id, $full = false) {
        $data = User::select()->where('id', $id)->one();
        if($data) {
            $user = new User();
            $user->setId($data['id']);
            $user->setName($data['name']);
            $user->setBirthdate($data['birthdate']);
            $user->setCity($data['city']);
            $user->setWork($data['work']);
            $user->setAvatar($data['avatar']);
            $user->setCover($data['cover']);

            if($full) {
                $user->setFollowers([]);
                $user->setFollowing([]);
                $user->setPhotos([]);

                $followers = UserRelation::select()->where('user_to', $id)->get();
                $followersUsers = [];
                foreach($followers as $f) {
                    $userData = User::select()->where('id', $f['user_from'])->one();

                    $newUser = new User();
                    $newUser->setId($userData['id']);
                    $newUser->setName($userData['name']);
                    $newUser->setAvatar($userData['avatar']);

                    $followersUsers[] = $newUser;
                }
                $user->setFollowers($followersUsers);

                $following = UserRelation::select()->where('user_to', $id)->get();
                $followingUsers = [];
                foreach($following as $f) {
                    $userData = User::select()->where('id', $f['user_to'])->one();

                    $newUser = new User();
                    $newUser->setId($userData['id']);
                    $newUser->setName($userData['name']);
                    $newUser->setAvatar($userData['avatar']);

                    $followingUsers[] = $newUser;
                }
                $user->setFollowing($followingUsers);

                $photos = PostHandler::getPhotosFrom($id);
                $user->setPhotos($photos);
            }

            return $user;
        }

        return false;
    }

    public static function idExists($id) {
        $user = User::select()->where('id', $id)->one();
        return $user ? true : false;
    }

    public static function verifyLogin($email, $password) {
        $user = User::select()->where('email', $email)->one();

        if($user) {
            if(password_verify($password, $user['password'])) {
                $token = md5(time().rand(0, 9999).time());

                User::update()
                    ->set('token', $token)
                    ->where('email', $email)
                ->execute();

                return $token;
            }
        }

        return false;
    }

}