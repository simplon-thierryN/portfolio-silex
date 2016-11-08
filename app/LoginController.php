<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 10/10/2016
 * Time: 12:15
 */

use Symfony\Component\HttpFoundation\Request;
use Portfolio\Domain\User;

/**
 * Login password
 */
$app->get('/login/adminTonyNoel', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login_tony');

