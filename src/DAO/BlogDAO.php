<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 11/10/2016
 * Time: 11:35
 */
namespace Portfolio\DAO;

use Portfolio\Domain\Blog;

class BlogDAO extends DAO {

    public function findAll(){
        $req = "select * from blog order by date desc";
        $result = $this->getDb()->fetchAll($req);

        $blog = array();
        foreach ($result as $row){
            $blogId = $row['blog_id'];
            $blog[$blogId] = $this->buildObject($row);
        }
        return $blog;
    }

    /**
     * @param $articleId
     * @return Blog
     * @throws \Exception
     */
   public function findById($articleId){
       $req = "select * from blog where blog_id=?";
       $result = $this->getDb()->fetchAssoc($req, array($articleId));

       if ($result){
           return $this->buildObject($result);
       }
       else{
           throw new \Exception("No article matching id " . $articleId);
       }
   }

    public function saveArticle(Blog $blog){
        $blogData = array(
            'blog_title' => $blog->getTitle(),
            'blog_content' => $blog->getContent(),
            'blog_url'=>$blog->getUrl(),
            'blog_alt'=>$blog->getAlt(),
            'date'=>$blog->getDate()
        );

        if($blog->getId()){
            $this->getDb()->update('blog',$blogData, array('blog_id'=>$blog->getId()));
        }
        else{
            $this->getDb()->insert('blog', $blogData);
            $id = $this->getDb()->lastInsertId();
            $blog->setId($id);
        }
    }

    public function deleteArticle($blogId){
        $this->getDb()->delete('blog',array('blog_id' => $blogId));
    }

    protected function buildObject($row)
    {
        $blog = new Blog();
        $blog->setId($row['blog_id']);
        $blog->setTitle($row['blog_title']);
        $blog->setContent($row['blog_content']);
        $blog->setUrl($row['blog_url']);
        $blog->setAlt($row['blog_alt']);
        $blog->setDate($row['date']);
        return $blog;
    }
}