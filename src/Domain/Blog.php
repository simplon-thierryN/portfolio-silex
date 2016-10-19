<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 10/10/2016
 * Time: 15:39
 */
namespace Portfolio\Domain;

class Blog{

    /**
     * Blog id
     * @var integer
     */
    private $id;

    /**
     * Blog title
     * @var string
     */
    private $title;

    /**
     * Blog content
     * @var string
     */
    private $content;

    /**
     * Blog url
     * @var string
     */
    private $url;

    /**
     * Blog alt
     * @var string
     */
    private $alt;

    /**
     * Blog date
     * @var date
     */
    private $date;



    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * @return date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}