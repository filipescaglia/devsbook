<?php
namespace src\models;
use \core\Model;

class User extends Model {

    private $id;
    private $email;
    private $name;

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