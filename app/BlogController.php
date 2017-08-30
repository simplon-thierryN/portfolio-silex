<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 10/10/2016
 * Time: 16:05
 */
use Symfony\Component\HttpFoundation\Request;
use Portfolio\Domain\Blog;
use Portfolio\Domain\BlogPicture;
use Portfolio\Form\BlogPicType;
use Portfolio\Form\BlogType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



/**
 * Show all blog article & Create blog article
 */
$app->match('/blog', function (Request $request) use ($app){
    $blog = $app['dao.blog']->findAll();

    $article = new Blog();
    $articleForm = $app['form.factory']->create(BlogType::class, $article)
         ->add('date',DateType::class,array(
             'widget' => 'choice'
         ));
    $articleForm->handleRequest($request);

    if($articleForm->isSubmitted()){

        $file = $article->getUrl();
        $articleTitle = $article->getTitle();
        $pregTitle = preg_replace('/\s+/', '_', $articleTitle);

        $filename = $file->getClientOriginalName();
        $pregFile = preg_replace('/\s+/', '_', $filename);

        $folderPath = __DIR__ . '/../web/images/blog/'.$pregTitle;

        if(!file_exists($folderPath)){
            mkdir($folderPath);
            $file->move($folderPath,$pregFile);

            $article->setUrl($pregFile);
            $article->setTitle($pregTitle);
            $title = explode(".", $pregFile);
            $article->setAlt($title[0]);
            $date = $article->getDate()->format('Y/m/d H:i:s');

            $article->setDate($date);

            $picture = new BlogPicture();
            $picture->setTitle($title[0]);
            $picture->setUrl($pregFile);
            $picture->setBlog($article);

            $app['dao.blog']->saveArticle($article);
            $app['dao.blogPic']->save($picture);

        }
        return $app->redirect('blog');
    }

    return $app['twig']->render('blog.html.twig',array(
        'blog'=> $blog,
        'articleForm'=>$articleForm->createView()
    ));
})->bind('blog');

/**
 * show blog article detail & update this blog article
 */
$app->match('/blog/article/{articleId}', function ($articleId, Request $request) use ($app){
    $article = $app['dao.blog']->findById($articleId);
    $articleTitle = $article->getTitle();
    $articleDate = $article->getDate();
    $album = $app['dao.blogPic']->findById($articleId);

    $picture = new BlogPicture();
    $addPic = $app['form.factory']->create(BlogPicType::class,$picture);
    $updateBlog = $app['form.factory']->create(BlogType::class,$article)
//        ->add('date',DateType::class,array(
//            'widget' => 'single_text'
//        ))
    ;
    $updateBlog->remove('url');


    $addPic->handleRequest($request);
    $updateBlog->handleRequest($request);

    if($addPic->isSubmitted()){

        $picture->setBlog($article);
        $file = $picture->getUrl();
        $pregTitle = preg_replace('/\s+/', '_', $articleTitle);

        $path = __DIR__ . '/../web/images/blog/'.$pregTitle;

        $filename = $file->getClientOriginalName();
        $pregFile = preg_replace('/\s+/', '_', $filename);

        $pathFile = $path.'/'.$pregFile;

        if(!file_exists($pathFile)) {
            $file->move($path, $pregFile);
            $picture->setUrl($pregFile);
            $title = explode(".", $filename);
            $picture->setTitle($title[0]);
            $app['dao.blogPic']->save($picture);

            return $app->redirect($articleId);
        }
        return $app->redirect($articleId);
    }

    if($updateBlog->isSubmitted()){
        $newTitle = $article->getTitle();

        $pregTitle = preg_replace('/\s+/', '_', $articleTitle);

        $pregNewTitle = preg_replace('/\s+/', '_', $newTitle);
        $path = __DIR__ . '/../web/images/blog/';
        rename($path.$pregTitle,$path.$pregNewTitle);

        $article->setDate($articleDate);

        $article->setTitle($pregNewTitle);
        $app['dao.blog']->saveArticle($article);

    }
    return $app['twig']->render('blogArticle.html.twig',array(
        'article'=>$article,
        'album'=>$album,
        'addPic'=>$addPic->createView(),
        'updateBlog'=>$updateBlog->createView()
    ));
})->bind('article');

/**
 * Update img blog article
 */
$app->post('/update/img_blog/{articleId}', function ($articleId, Request $request) use ($app){
    $data = $request->request->get('img');
    $article = $app['dao.blog']->findById($articleId);
    $articleTitle = $article->getTitle();
    $pregTitle = preg_replace('/\s+/', '_', $articleTitle);

    $title = explode(".", $data);
    $article->setAlt($title[0]);
    $article->setUrl($data);

    $app['dao.blog']->saveArticle($article);
    return "c'est good";});

/**
 * Delete blog article
 */
$app->get('/delete/article/{articleId}', function ($articleId, Request $request) use ($app){

    $article = $app['dao.blog']->findById($articleId);
    $articleTitle = $article->getTitle();
    $pregTitle = preg_replace('/\s+/', '_', $articleTitle);

    $folderPath =  __DIR__ . '/../web/images/blog/'.$pregTitle;

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

        $app['dao.blogPic']->deleteAllPic($articleId);
        $app['dao.blog']->deleteArticle($articleId);

        return $app->redirect('/blog');
    }

    return $app->redirect('/blog');
})->bind('delete_article');

/**
 * Delete img in blog article
 */
$app->get('/delete/article/{articleId}/{imgId}', function ($articleId, $imgId, Request $request) use ($app){
    $picture = $app['dao.blogPic']->findPicById($imgId);
    $article = $app['dao.blog']->findById($articleId);

    $title = $article->getTitle();
    $file = $picture->getUrl();
    $folderPath = __DIR__ . '/../web/images/blog/'.$title.'/';
    unlink($folderPath.$file);
    $app['dao.blogPic']->delete($imgId);
    return $app->redirect('/blog/article/'.$articleId);
})->bind('deleteImg');

