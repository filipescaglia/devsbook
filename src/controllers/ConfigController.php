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
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('config', [
            'flash' => $flash,
            'loggedUser' => $this->loggedUser,
        ]);
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

            UserHandler::updateBirthdate($birthdate, $userId);
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

            UserHandler::updateEmail($email, $userId);
        }

        if(!empty($newPassword) && !empty($newPasswordConf)) {
            if($newPassword !== $newPasswordConf) {
                $_SESSION['flash'] = 'Senhas não conferem!';
                $this->redirect('/config');
            }

            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            UserHandler::updatePassword($hash, $userId);
        }

        if(!empty($city)) {
            UserHandler::updateCity($city, $userId);
        }

        if(!empty($name)) {
            UserHandler::updateName($name, $userId);
        }

        if(!empty($work)) {
            UserHandler::updateWork($work, $userId);
        }

        $this->redirect('/config');

    }

}