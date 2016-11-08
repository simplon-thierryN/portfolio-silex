<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 02/10/2016
 * Time: 00:03
 */

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../app/config/dev.php';
require __DIR__.'/../app/app.php';
require __DIR__.'/../app/AlbumController.php';
require __DIR__.'/../app/LoginController.php';
require __DIR__.'/../app/BlogController.php';
//require __DIR__.'/../app/MailController.php';

$app->run();