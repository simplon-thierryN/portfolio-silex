<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 02/10/2016
 * Time: 00:17
 */
use Symfony\Component\HttpFoundation\Request;
use Portfolio\Domain\Album;
use Portfolio\Domain\Picture;
use Portfolio\Form\AlbumType;
use Portfolio\Form\PictureType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

$fs = new Filesystem();


/**
 * Home
 */
$app->match('/', function (Request $request) use ($app){
    $categories = $app['dao.album']->findByCategory();
    $albums = $app['dao.album']->findAllAlbum();

    $albumPic = new Album();
    $albumForm = $app['form.factory']->create(AlbumType::class, $albumPic);
    $albumForm->handleRequest($request);

    $picture = new Picture();
    $picture->setAlbum($albumPic);

    if ($albumForm->isSubmitted()){
        $file = $albumPic->getUrl();
        $path = __DIR__ . '/../web/images/album';
        $filename = $file->getClientOriginalName();

        $file->move($path,$filename);
        $albumPic->setUrl($filename);
        $picture->setUrl($filename);
        $title = explode(".", $filename);
        $picture->setTitle($title[0]);

        $app['dao.album']->saveAlbum($albumPic);
        $app['dao.pic']->savePic($picture);
        return $app->redirect('/');
    }
    $albumFormView = $albumForm->createView();

    return $app['twig']->render('index.html.twig',array(
        'categories' => $categories,
        'albums'=>$albums,
        'albumForm'=>$albumFormView
    ));
})->bind('home');

/**
 *
 * Details album
 */
$app->match('album/{albumId}', function ($albumId, Request $request) use ($app){
    $album = $app['dao.album']->findById($albumId);
    $pictures = $app['dao.pic']->findAllPic($albumId);
    $addPic = new Picture();

    $updateForm = $app['form.factory']->create(AlbumType::class, $album);
    $addForm = $app['form.factory']->create(PictureType::class,$addPic);

    $updateForm->handleRequest($request);
    $addForm->handleRequest($request);

    if($updateForm->isSubmitted()){
        $file = $album->getUrl();
        $path = __DIR__ . '/../web/images/album/';
        $filename = $file->getClientOriginalName();

        $file->move($path,$filename);
        $album->setUrl($filename);
        $app['dao.album']->saveAlbum($album);
        return $app->redirect($albumId);
    }

    if($addForm->isSubmitted()){
        $file = $addPic->getUrl();
        $path = __DIR__ . '/../web/images/album/';
        $filename = $file->getClientOriginalName();

        $file->move($path,$filename);
        $addPic->setUrl($filename);
        $addPic->setAlbum($album);
        $app['dao.pic']->savePic($addPic);
        return $app->redirect($albumId);
    }
    return $app['twig']->render('updateAlbum.html.twig', array(
        'album'=>$album,
        'updateAlbum'=>$updateForm->createView(),
        'pictures'=>$pictures,
        'addForm'=>$addForm->createView()
    ));
})->bind('album');

$app->get('delete/album/{albumId}', function($albumId, Request $request) use ($app){
    $app['dao.pic']->deleteAllPic($albumId);
    $app['dao.album']->deleteAlbum($albumId);

    return $app->redirect('/');
})->bind('delete_album');

$app->get('delete/album/{albumId}/{pictureId}', function ($albumId, $pictureId, Request $request) use ($app){
    $app['dao.pic']->deletePicture($pictureId);
    return $app->redirect('/album/'.$albumId);
})->bind('delete_picture');







//
//$app->get('/tarif', function () use ($app){
//    return $app['twig']->render('tarif.html.twig');
//})->bind('tarif');
//
//$app->get('/blog', function () use ($app){
//    return $app['twig']->render('blog.html.twig');
//})->bind('blog');
//
//$app->get('/contact', function () use ($app){
//    return $app['twig']->render('contact.html.twig');
//})->bind('contact');
//
//$app->get('/about', function () use ($app){
//    return $app['twig']->render('about.html.twig');
//})->bind('about');