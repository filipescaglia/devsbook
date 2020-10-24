<?php
namespace src\handlers;

use \src\models\Post;
use \src\models\Post_Like;
use \src\models\User;
use \src\models\User_Relation;

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

    public static function addLike($id, $loggedUserId) {
        Post_Like::insert([
            'id_post' => $id,
            'id_user' => $loggedUserId,
            'created_at' => date('Y-m-d H:i:s')
        ])->execute();
    }

    public static function deleteLike($id, $loggedUserId) {
        Post_Like::delete()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
        ->execute();
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

            $likes = Post_Like::select()->where('id_post', $p['id'])->get();

            $newPost->likeCount = count($likes);
            $newPost->liked = self::isLiked($p['id'], $loggedUserId);

            $newPost->comments = [];

            $posts[] = $newPost;
        }

        return $posts;
    }

    public static function isLiked($id, $loggedUserId) {
        $myLike = Post_Like::select()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
        ->get();

        if(count($myLike) > 0) return true;
        else return false;
    }

    public static function getHomeFeed($idUser, $page) {
        $perPage = 2;

        $userList = User_Relation::select()->where('user_from', $idUser)->get();
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