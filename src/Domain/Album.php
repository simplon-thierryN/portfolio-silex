<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 02/10/2016
 * Time: 11:34
 */
namespace Portfolio\Domain;

use Symfony\Component\Validator\Constraints\DateTime;

class Album{

    /**
     * Album id
     * @var integer
     */
    private $id;

    /**
     * Album title
     * @var string
     */
    private $title;

    /**
     * Album url
     * @var string
     */
    private $url;

    /**
     * Album category
     * @var string
     */
    private $category;

    private $date;

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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

}