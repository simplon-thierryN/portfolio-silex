<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 08/10/2016
 * Time: 14:47
 */
namespace Portfolio\Domain;

class Picture{

    /**
     * Picture id
     * @var integer
     */
    private $id;

    /**
     * Picture url
     * @var string
     */
    private $url;

    /**
     * Picture title
     * @var string
     */
    private $title;

    /**
     * associated article
     * @var \Portfolio\Domain\Album
     */
    private $album;

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
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
     * @param Album $album
     */
    public function setAlbum(Album $album)
    {
        $this->album = $album;
    }

    /**
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }
}