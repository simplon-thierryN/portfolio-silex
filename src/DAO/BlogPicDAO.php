<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 11/10/2016
 * Time: 16:05
 */
namespace Portfolio\DAO;

use Portfolio\Domain\BlogPicture;

class BlogPicDAO extends DAO {

    /**
     * @var \Portfolio\DAO\BlogDAO
     */
    private $blogDAO;


    public function setBlogDAO(BlogDAO $blogDAO){
        $this->blogDAO = $blogDAO;
    }

    /**
     * Find all img by blog id
     * @param $articleId
     * @return array
     */
    public function findById($articleId){

        $article = $this->blogDAO->findById($articleId);

        $req = "select * from blog_pic where blog_id=?";
        $result = $this->getDb()->fetchAll($req, array($articleId));

        $blogPic = array();
        foreach ($result as $row){
            $picId = $row['pic_id'];
            $blog = $this->buildObject($row);
            $blog->setBlog($article);
            $blogPic[$picId]= $blog;
        }

        return $blogPic;
    }

    public function findPicById($imgId){
        $req = " select * from blog_pic where pic_id=? ";
        $result = $this->getDb()->fetchAssoc($req, array($imgId));
        return $this->buildObject($result);
    }

    /**
     * Save img in blog article
     * @param BlogPicture $blogPicture
     */
    public function save(BlogPicture $blogPicture){
        $pictureData = array(
            'pic_url'=>$blogPicture->getUrl(),
            'pic_title'=>$blogPicture->getTitle(),
            'blog_id'=>$blogPicture->getBlog()->getId()
        );
        $this->getDb()->insert('blog_pic',$pictureData);
        $id = $this->getDb()->lastInsertId();
        $blogPicture->setId($id);
    }

    public function deleteAllPic($imgId){
        $this->getDb()->delete('blog_pic',array('blog_id' => $imgId));
    }

    /**
     * Delete img from blog article
     * @param $imgId
     */
    public function delete($imgId){
        $this->getDb()->delete('blog_pic',array('pic_id' => $imgId));
    }


    protected function buildObject($row)
    {
        $blogPic = new BlogPicture();
        $blogPic->setId($row['pic_id']);
        $blogPic->setTitle($row['pic_title']);
        $blogPic->setUrl($row['pic_url']);

        if(array_key_exists('blog_id', $row)){
            $blogId = $row['blog_id'];
            $blog = $this->blogDAO->findById($blogId);
            $blogPic->setBlog($blog);
        }
        return $blogPic;
    }
}