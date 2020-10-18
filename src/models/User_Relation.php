<?php
namespace src\models;
use \core\Model;

class User_Relation extends Model {

    private $avatar;
    private $birthdate;
    private $email;
    private $id;
    private $name;

    public function getAvatar() {
        return $this->avatar;
    }

    public function getBirthdate() {
        return $this->birthdate;
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getName() {
        return $this->name;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function setBirthdate($birthdate) {
        $this->birthdate = $birthdate;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setName($name) {
        $this->name = $name;
    }

}