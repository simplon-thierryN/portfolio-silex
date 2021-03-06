<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 02/10/2016
 * Time: 11:46
 */
namespace Portfolio\DAO;

use Portfolio\Domain\Album;

class AlbumDAO extends DAO {

    /**
     * @return array Category
     */
    public function findByCategory(){
        $req = "select distinct alb_category from album";
        $result = $this->getDb()->fetchAll($req);
        return $result;
    }

    /**
     * @return array Album
     */
    public function findAllAlbum(){
        $req = "select * from album order by alb_id desc";
        $result = $this->getDb()->fetchAll($req);

        $albums = array();
        foreach ($result as $row){
            $albumId = $row['alb_id'];
            $albums[$albumId]= $this->buildObject($row);
        }

        return $albums;
    }

    /**
     * @param $albumId
     * @return Album
     */
    public function findById($albumId){
        $req = " select * from album where alb_id=? ";
        $result = $this->getDb()->fetchAssoc($req, array($albumId));

        return $this->buildObject($result);

    }

    /**
     * Delete Album
     * @param $albumId
     */
    public function deleteAlbum($albumId){
        $this->getDb()->delete('album',array('alb_id' => $albumId));
    }

    /**
     * @param $row
     * @return Album
     */
    protected function buildObject($row){
        $album = new Album();
        $album->setId($row['alb_id']);
        $album->setTitle($row['alb_title']);
        $album->setUrl($row['alb_url']);
        $album->setCategory($row['alb_category']);
        return $album;
    }

    /**
     * @param Album $album
     */
    public function saveAlbum(Album $album){
        $albumData = array(
            'alb_url' => $album->getUrl(),
            'alb_title' => $album->getTitle(),
            'alb_category' => $album->getCategory()
        );

        if($album->getId()){
            $this->getDb()->update('album',$albumData, array('alb_id'=>$album->getId()));
        }
        else{
            $this->getDb()->insert('album', $albumData);
            $id = $this->getDb()->lastInsertId();
            $album->setId($id);
        }
    }
}