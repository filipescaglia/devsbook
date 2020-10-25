<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/login', 'LoginController@signin');
$router->post('/login', 'LoginController@signinAction');

$router->get('/register', 'LoginController@signup');
$router->post('/register', 'LoginController@signupAction');

$router->post('/post/new', 'PostController@new');
$router->get('/post/{id}/delete', 'PostController@delete');

$router->get('/profile/{id}/photos', 'ProfileController@photos');
$router->get('/profile/{id}/friends', 'ProfileController@friends');
$router->get('/profile/{id}/follow', 'ProfileController@follow');
$router->get('/profile/{id}', 'ProfileController@index');
$router->get('/profile', 'ProfileController@index');

$router->get('/friends', 'ProfileController@friends');

$router->get('/photos', 'ProfileController@photos');

$router->get('/search', 'SearchController@index');

$router->get('/config', 'ConfigController@index');
$router->post('/config', 'ConfigController@update');

$router->get('/ajax/like/{id}', 'AjaxController@like');
$router->post('/ajax/comment', 'AjaxController@comment');
$router->post('/ajax/upload', 'AjaxController@upload');

$router->get('/logout', 'LoginController@logout');


//$router->get('/search', '');
//$router->get('/photos', '');
//$router->get('/config', '');