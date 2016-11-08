<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 02/10/2016
 * Time: 00:11
 */
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\FormServiceProvider;
use Portfolio\Form\ContactType;
use Symfony\Component\BrowserKit\Response;

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
            'logout' => array('logout_path' => '/admin/logout', 'invalidate_session' => true),
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


$app->match('/about', function () use($app){
    return $app['twig']->render('about.html.twig');
})->bind('about');



$app->register(new Silex\Provider\SwiftmailerServiceProvider());
//$app['swiftmailer.options'] = array(
//    'host' => 'smtp.gmail.com',
//    'port' => '465',
//    'username' => 'thierryngn@gmail.com',
//    'password' => 'Touboul.0285',
//    'encryption' => 'ssl',
//    'auth_mode' => 'login'
//);
$app['swiftmailer.options'] = array(
    'host' => 'mail.gandi.net',
    'port' => '587',
    'username' => '',
    'password' => '',
    'encryption' => 'tls',
    'auth_mode' => 'SMTP'
);


$app->match('/contact', function (Request $request) use($app){

    $data = array(
        'subject',
        'name',
        'email',
        'message'
    );

    $formEmail = $app['form.factory']->createBuilder(ContactType::class);

    $form = $formEmail->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        $app['swiftmailer.use_spool'] = false;

        $app['mailer']->send(\Swift_Message::newInstance()
            ->setSubject($data['subject'])
            ->setFrom(array($data['email']))
            ->setTo(array('thierryngn@gmail.com'))
            ->setBody($app['twig']->render('email.html.twig', array(
                'subject'=>$data['subject'],
                'name'=>$data['name'],
                'email'=>$data['email'],
                'message'=>$data['message']
            )),'text/html'));

        return $app->redirect('/contact');
    }


    return $app['twig']->render('contact.html.twig',array(
        'form'=>$form->createView()
    ));
})->bind('contact');

$app->get('/tarifs',function () use($app){
    return $app['twig']->render('tarif.html.twig');
})->bind('tarifs');


return $app;