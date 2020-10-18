<?php
namespace src\models;
use \core\Model;

class User extends Model {

    private $age;
    private $avatar;
    private $birthdate;
    private $city;
    private $cover;
    private $email;
    private $followers;
    private $following;
    private $id;
    private $name;
    private $photos;
    private $work;

    public function getAge() {
        return $this->age;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getBirthdate() {
        return $this->birthdate;
    }

    public function getCity() {
        return $this->city;
    }

    public function getCover() {
        return $this->cover;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFollowers() {
        return $this->followers;
    }

    public function getFollowing() {
        return $this->following;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPhotos() {
        return $this->photos;
    }

    public function getWork() {
        return $this->work;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function setBirthdate($birthdate) {
        $this->birthdate = $birthdate;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setCover($cover) {
        $this->cover = $cover;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setFollowers($followers) {
        $this->followers = $followers;
    }

    public function setFollowing($following) {
        $this->following = $following;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPhotos($photos) {
        $this->photos = $photos;
    }

    public function setWork($work) {
        $this->work = $work;
    }

}