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

$app->match('/blog', function (Request $request) use ($app){
    $blog = $app['dao.blog']->findAll();

    $article = new Blog();
    $articleForm = $app['form.factory']->create(BlogType::class, $article);
    $articleForm->handleRequest($request);

    if($articleForm->isSubmitted()){

        $file = $article->getUrl();
        $path = __DIR__ . '/../web/images/blog/';
        $filename = $file->getClientOriginalName();

        $file->move($path,$filename);
        $article->setUrl($filename);
        $title = explode(".", $filename);
        $article->setAlt($title[0]);
        $date = $article->getDate()->format('Y-m-d');
        $article->setDate($date);

        $picture = new BlogPicture();
        $picture->setTitle($title[0]);
        $picture->setUrl($filename);
        $picture->setBlog($article);

        $app['dao.blog']->saveArticle($article);
        $app['dao.blogPic']->save($picture);

        return $app->redirect('blog');
    }

    return $app['twig']->render('blog.html.twig',array(
        'blog'=> $blog,
        'articleForm'=>$articleForm->createView()
    ));
})->bind('blog');

$app->get('/delete/article/{articleId}', function ($articleId, Request $request) use ($app){
    $app['dao.blog']->deleteArticle($articleId);
    return $app->redirect('/blog');
})->bind('delete_article');


$app->match('/blog/article/{articleId}', function ($articleId, Request $request) use ($app){
    $article = $app['dao.blog']->findById($articleId);
    $album = $app['dao.blogPic']->findPicById($articleId);

    $picture = new BlogPicture();
    $addPic = $app['form.factory']->create(BlogPicType::class,$picture);
    $updateBlog = $app['form.factory']->create(BlogType::class,$article);
    $updateBlog->remove('url');

    $addPic->handleRequest($request);
    $updateBlog->handleRequest($request);

    if($addPic->isSubmitted()){
        $picture->setBlog($article);
        $file = $picture->getUrl();
        $path = __DIR__ . '/../web/images/blog/';
        $filename = $file->getClientOriginalName();

        $file->move($path,$filename);
        $picture->setUrl($filename);
        $title = explode(".", $filename);
        $picture->setTitle($title[0]);
        $app['dao.blogPic']->save($picture);

        return $app->redirect($articleId);
    }

    if($updateBlog->isSubmitted()){
        $app['dao.blog']->saveArticle($article);
        return $app->redirect($articleId);
    }
    return $app['twig']->render('blogArticle.html.twig',array(
        'article'=>$article,
        'album'=>$album,
        'addPic'=>$addPic->createView(),
        'updateBlog'=>$updateBlog->createView()
    ));
})->bind('article');

$app->post('/update/img_blog/{articleId}', function ($articleId, Request $request) use ($app){
    $data = $request->request->get('img');

    $article = $app['dao.blog']->findById($articleId);
    $article->setUrl($data);
    $title = explode(".", $data);
    $article->setAlt($title[0]);
    $app['dao.blog']->saveArticle($article);
    return "c'est good";});

