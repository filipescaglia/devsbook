<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/login', 'LoginController@signin');
$router->post('/login', 'LoginController@signinAction');

$router->get('/register', 'LoginController@signup');
$router->post('/register', 'LoginController@signupAction');

$router->post('/post/new', 'PostController@new');

$router->get('/profile/{id}', 'ProfileController@index');
$router->get('/profile', 'ProfileController@index');

$router->get('/logout', 'LoginController@logout');

//$router->get('/search', '');
//$router->get('/friends', '');
//$router->get('/photos', '');
//$router->get('/config', '');