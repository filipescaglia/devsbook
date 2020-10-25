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

    public function upload() {
        $response = ['error' => ''];

        if(isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])) {

            $photo = $_FILES['photo'];

            $maxWidth = 800;
            $maxHeight = 800;

            if(in_array($photo['type'], ['image/png', 'image/jpg', 'image/jpeg'])) {

                list($widthOrig, $heightOrig) = getimagesize($photo['tmp_name']);
                $ratio = $widthOrig / $heightOrig;

                $newWidth = $maxWidth;
                $newHeight = $maxHeight;
                $ratioMax = $maxWidth / $maxHeight;

                if($ratioMax > $ratio) {
                    $newWidth = $newHeight * $ratio;
                } else {
                    $newHeight = $newWidth / $ratio;
                }

                $finalImage = imagecreatetruecolor($newWidth, $newHeight);
                switch($photo['type']) {
                    case 'image/png':
                        $image = imagecreatefrompng($photo['tmp_name']);
                    break;

                    case 'image/jpg':
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($photo['tmp_name']);
                    break;
                }

                imagecopyresampled(
                    $finalImage, $image,
                    0, 0, 0, 0,
                    $newWidth, $newHeight, $widthOrig, $heightOrig
                );

                $photoName = md5(time() . rand(0, 9999)) . '.jpg';
                imagejpeg($finalImage, 'media/uploads/' . $photoName);

                PostHandler::addPost(
                    $this->loggedUser->getId(),
                    'photo',
                    $photoName
                );

            }

        } else {
            $response['error'] = 'Nenhuma imagem enviada';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

}