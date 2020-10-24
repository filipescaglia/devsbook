<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;

class ConfigController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false)
            $this->redirect('/login');
    }

    public function index() {
        $user = UserHandler::getUser($this->loggedUser->getId());

        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('config', [
            'flash' => $flash,
            'loggedUser' => $this->loggedUser,
            'user' => $user
        ]);
    }

    private function cutImage($file, $width, $height, $folder) {
        list($widthOrig, $heightOrig) = getimagesize($file['tmp_name']);
        $ratio = $widthOrig / $heightOrig;

        $newWidth = $width;
        $newHeight = $newWidth / $ratio;

        if($newHeight < $height) {
            $newHeight = $height;
            $newWidth = $newHeight * $ratio;
        }

        $x = $width - $newWidth;
        $y = $height - $newHeight;
        $x = $x < 0 ? $x / 2 : $x;
        $y = $y < 0 ? $y / 2 : $y;

        $finalImage = imagecreatetruecolor($width, $height);
        switch($file['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file['tmp_name']);
            break;

            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
            break;
        }

        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0, 0,
            $newWidth, $newHeight, $widthOrig, $heightOrig
        );

        $fileName = md5(time().rand(0, 9999)) . 'jpg';

        imagejpeg($finalImage, $folder . '/' . $fileName);

        return $fileName;
    }

    public function update() {
        $userId = $this->loggedUser->getId();

        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email');
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $newPassword = filter_input(INPUT_POST, 'password');
        $newPasswordConf = filter_input(INPUT_POST, 'password-conf');
        $work = filter_input(INPUT_POST, 'work', FILTER_SANITIZE_SPECIAL_CHARS);

        $updateFields = [];

        if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
            $newAvatar = $_FILES['avatar'];

            if(in_array($newAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
                $avatarName = $this->cutImage($newAvatar, 200, 200, 'media/avatars');
                $updateFields['avatar'] = $avatarName;
            }
        }

        if(isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
            $newCover = $_FILES['cover'];

            if(in_array($newCover['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
                $coverName = $this->cutImage($newCover, 850, 310, 'media/covers');
                $updateFields['cover'] = $coverName;
            }
        }

        if(!empty($birthdate)) {
            $birthdate = explode('/', $birthdate);
            if(count($birthdate) !== 3) {
                $_SESSION['flash'] = 'Data de nascimento inválida!';
                $this->redirect('/config');
            }

            $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
            if(strtotime($birthdate) === false) {
                $_SESSION['flash'] = 'Data de nascimento inválida!';
                $this->redirect('/config');
            }

            $updateFields['birthdate'] = $birthdate;
        }

        if(!empty($email)) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            if($email === false) {
                $_SESSION['flash'] = 'E-mail inválido!';
                $this->redirect('/config');
            }

            $emailExists = UserHandler::emailExists($email, true);
            if($emailExists !== false) {
                if($emailExists['id'] !== $userId) {
                    $_SESSION['flash'] = 'E-mail em uso por outro usuário!';
                    $this->redirect('/config');
                }
            }

            $updateFields['email'] = $email;
        }

        if(!empty($newPassword) && !empty($newPasswordConf)) {
            if($newPassword !== $newPasswordConf) {
                $_SESSION['flash'] = 'Senhas não conferem!';
                $this->redirect('/config');
            }

            $updateFields['password'] = $newPassword;
        }

        if(!empty($city)) {
            $updateFields['city'] = $city;
        }

        if(!empty($name)) {
            $updateFields['name'] = $name;
        }

        if(!empty($work)) {
            $updateFields['work'] = $work;
        }

        UserHandler::updateUser($updateFields, $this->loggedUser->getId());

        $this->redirect('/config');

    }

}