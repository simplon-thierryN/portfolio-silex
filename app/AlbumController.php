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
 *
 * Show all album & add album
 * Homepage
 *
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
        $time =date('Y/m/d H:i:s');

        $file = $albumPic->getUrl();
        $albumTitle = $albumPic->getTitle();
        $pregTitle = preg_replace('/\s+/', '_', $albumTitle); //replace space by '_'

        $filename = $file->getClientOriginalName();
        $pregFile = preg_replace('/\s+/', '_', $filename);


        $albumPic->setUrl($pregFile);
        $albumPic->setTitle($pregTitle);
        $albumPic->setDate($time);
        $picture->setUrl($pregFile);
        $title = explode(".", $pregFile);
        $picture->setTitle($title[0]);

        $folderPath = __DIR__ . '/../web/images/album/'.$pregTitle;

        if(!file_exists($folderPath)){
            mkdir($folderPath);
            $file->move($folderPath,$pregFile);
            $app['dao.album']->saveAlbum($albumPic);
            $app['dao.pic']->savePic($picture);
        }


        return $app->redirect('/');
    }
    $albumFormView = $albumForm->createView();

    return $app['twig']->render('index.html.twig',array(
        'categories' => $categories,
        'albums'=>$albums,
        'albumForm'=>$albumFormView
    ));
})->bind('portfolio');

/**
 *
 *Show details album & update album
 * Details album
 *
 */
$app->match('/portfolio/album/{title}/{albumId}', function ($albumId, Request $request) use ($app){
    $album = $app['dao.album']->findById($albumId);
    $pictures = $app['dao.pic']->findAllPic($albumId);
    $title = $album->getTitle();
    $time =date('Y/m/d H:i:s');
    $category = $album->getCategory();
    $addPic = new Picture();
    $test =$album->getTitle().'/';

    $updateForm = $app['form.factory']->create(AlbumType::class, $album);
    $updateForm->remove('url');

    $addForm = $app['form.factory']->create(PictureType::class,$addPic);

    $updateForm->handleRequest($request);
    $addForm->handleRequest($request);


    if($updateForm->isSubmitted()){
        $newTitle = $album->getTitle();
        $path =  __DIR__ . '/../web/images/album/';
        $album->setDate($time);
        rename($path.$title,$path.$newTitle);
        $app['dao.album']->saveAlbum($album);
        return $app->redirect($albumId);
    }

    if($addForm->isSubmitted()){
        $file = $addPic->getUrl();
        $albumTitle = $album->getTitle();
        $pregTitle = preg_replace('/\s+/', '_', $albumTitle);

        $path = __DIR__ . '/../web/images/album/'.$pregTitle;
        $filename = $file->getClientOriginalName();
        $pregFile = preg_replace('/\s+/', '_', $filename);


        $pathFile = $path.'/'.$pregFile;
        if(!file_exists($pathFile)){
            $file->move($path,$pregFile);
            $addPic->setUrl($pregFile);
            $addPic->setAlbum($album);
            $album->setDate($time);
            $app['dao.album']->saveAlbum($album);
            $app['dao.pic']->savePic($addPic);
            return $app->redirect($albumId);
        }

        return $app->redirect($albumId);
    }

    return $app['twig']->render('updateAlbum.html.twig', array(
        'album'=>$album,
        'updateAlbum'=>$updateForm->createView(),
        'pictures'=>$pictures,
        'addForm'=>$addForm->createView(),
        'nameAlbum'=>$test
    ));
})->bind('album');

$app->get('portfolio/album/{title}/{albumId}/slider',function($albumId) use($app){
    $album =$app['dao.pic']->picForSlider($albumId);
    return $app->json($album);
});

/**
 *
 * update img album
 *
 */
$app->post('update/img_profil/{albumId}', function ($albumId, Request $request) use ($app){
    $data = $request->request->get('img');
    $album = $app['dao.album']->findById($albumId);

    $albumTitle = $album->getTitle();
    $pregTitle = preg_replace('/\s+/', '_', $albumTitle);

    $album->setUrl($data);
    $app['dao.album']->saveAlbum($album);

    return "c'est good";
});

/**
 *
 * delete album & all img in album
 *
 */
/**
 * @param $albumId
 * @param Request $request
 * @return \Symfony\Component\HttpFoundation\RedirectResponse
 */
$app->match('delete/album/{albumId}', function($albumId, Request $request) use ($app){
    $album = $app['dao.album']->findById($albumId);
    $albumTitle = $album->getTitle();
    $pregTitle = preg_replace('/\s+/', '_', $albumTitle);

    $folderPath =  __DIR__ . '/../web/images/album/'.$pregTitle;


    if(is_dir($folderPath)){
        // get all file names
        $folder = glob($folderPath.'/*');
        // iterate files
        foreach($folder as $file){
            if(file_exists($file) && is_file($file )){
                unlink($file);
            }
        }
        rmdir($folderPath);

        $app['dao.pic']->deleteAllPic($albumId);
        $app['dao.album']->deleteAlbum($albumId);

        return $app->redirect('/');
    }
//    return $app->redirect('/');
})->bind('delete_album');


/**
 *
 *Delete img
 *
 */
$app->get('delete/album/{albumId}/{pictureId}', function ($albumId, $pictureId, Request $request) use ($app){
    $picture = $app['dao.pic']->findById($pictureId);
    $album = $app['dao.album']->findById($albumId);
    $file = $picture->getUrl();
    $albumTitle = $album->getTitle();
    $folderPath = __DIR__ . '/../web/images/album/'.$albumTitle.'/';
    unlink($folderPath.$file);
    $app['dao.pic']->deletePicture($pictureId);
    return $app->redirect('/portfolio/album/'.$albumTitle.'/'.$albumId);
})->bind('delete_picture');


