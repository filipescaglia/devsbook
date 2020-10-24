<?php
namespace src\models;
use \core\Model;

class Post_Comment extends Model {

    private $id;
    private $idUser;
    private $type;
    private $createdAt;
    private $body;

    public function getBody() {
        return $this->body;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getId() {
        return $this->id;
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function getType() {
        return $this->type;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }

    public function setType($type) {
        $this->type = $type;
    }

}