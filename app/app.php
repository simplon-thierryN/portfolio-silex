<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 02/10/2016
 * Time: 00:11
 */
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());

$app->register(new Silex\Provider\AssetServiceProvider());

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(),array(
    'locale'=>'fr'
));

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login_tony', 'check_path' => '/login_check'),
            'users' => (function($app) {
                return new Portfolio\DAO\UserDAO($app['db']);
            }),
        ),
    ),
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../resources/views'
));

$app['dao.album'] = function ($app){
    return new Portfolio\DAO\AlbumDAO($app['db']);
};

$app['dao.pic'] = function ($app){
    $pictureDAO = new Portfolio\DAO\PictureDAO($app['db']);
    $pictureDAO->setAlbumDAO($app['dao.album']);
    return $pictureDAO;
};

$app['dao.user'] = function ($app){
    return new Portfolio\DAO\UserDAO($app['db']);
};

$app['dao.blog'] = function ($app){
    return new Portfolio\DAO\BlogDAO($app['db']);
};

$app['dao.blogPic'] = function ($app){
    $blogPicDao =  new Portfolio\DAO\BlogPicDAO($app['db']);
    $blogPicDao->setBlogDAO($app['dao.blog']);
    return $blogPicDao;
};