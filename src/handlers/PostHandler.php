<?php
namespace src\handlers;

use \src\models\Post;
use \src\models\User;
use \src\models\UserRelation;

class PostHandler {

    public static function addPost($idUser, $type, $body) {
        $body = trim($body);
        
        if(!empty($idUser) && !empty($body)) {
            Post::insert([
                'id_user' => $idUser,
                'type' => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'body' => $body
            ])->execute();
        }
    }

    public static function _postListToObject($loggedUserId, $postList) {
        $posts = [];
        foreach($postList as $p) {
            $newPost = new Post();
            $newPost->setId($p['id']);
            $newPost->setType($p['type']);
            $newPost->setCreatedAt($p['created_at']);
            $newPost->setBody($p['body']);
            $newPost->mine = false;

            if($p['id_user'] == $loggedUserId)
                $newPost->mine = true;

            $newUser = User::select()->where('id', $p['id_user'])->one();
            $newPost->user = new User();
            $newPost->user->setId($newUser['id']);
            $newPost->user->setName($newUser['name']);
            $newPost->user->setAvatar($newUser['avatar']);

            $newPost->likeCount = 0;
            $newPost->liked = false;

            $newPost->comments = [];

            $posts[] = $newPost;
        }

        return $posts;
    }

    public static function getHomeFeed($idUser, $page) {
        $perPage = 2;

        $userList = UserRelation::select()->where('user_from', $idUser)->get();
        $users = [];
        foreach($userList as $u) {
            $users[] = $u['user_to'];
        }
        $users[] = $idUser;

        $postList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
        ->get();

        $total = Post::select()
            ->where('id_user', 'in', $users)
        ->count();
        $pageCount = ceil($total / $perPage);
        
        $posts = self::_postListToObject($idUser, $postList);

        return [
            'currentPage' => $page,
            'pageCount' => $pageCount,
            'posts' => $posts
        ];
    }

    public static function getUserFeed($idUser, $page, $loggedUserId) {
        $perPage = 2;

        $postList = Post::select()
            ->where('id_user', $idUser)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
        ->get();

        $total = Post::select()
            ->where('id_user', $idUser)
        ->count();
        $pageCount = ceil($total / $perPage);
        
        $posts = self::_postListToObject($loggedUserId, $postList);

        return [
            'currentPage' => $page,
            'pageCount' => $pageCount,
            'posts' => $posts
        ];
    }

    public static function getPhotosFrom($idUser) {
        $photosData = Post::select()
            ->where('id_user', $idUser)
            ->where('type', 'photo')
            ->get();

        $photos = [];
        foreach($photosData as $p) {
            $newPost = new Post();
            $newPost->setId($p['id']);
            $newPost->setCreatedAt($p['created_at']);
            $newPost->setBody($p['body']);
            $newPost->setType($p['type']);

            $photos[] = $newPost;
        }

        return $photos;
    }

}