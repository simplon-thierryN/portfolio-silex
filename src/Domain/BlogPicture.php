<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 11/10/2016
 * Time: 15:49
 */
namespace Portfolio\Domain;

class BlogPicture{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */

    private $url;
    /**
     * @var string
     */

    private $title;

    /**
     * @var \Portfolio\Domain\Blog
     */
    private $blog;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return Blog
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param Blog $blog
     */
    public function setBlog(Blog $blog)
    {
        $this->blog = $blog;
    }

}