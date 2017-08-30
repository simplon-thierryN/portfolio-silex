<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 08/10/2016
 * Time: 14:47
 */
namespace Portfolio\DAO;

use Portfolio\Domain\Picture;

class PictureDAO extends DAO{

    /**
     * @var \Portfolio\DAO\AlbumDAO
     */
    private $albumDAO;

    public function setAlbumDAO(AlbumDAO $albumDAO){
        $this->albumDAO = $albumDAO;
    }

    /**
     * @param $albumId
     * @return array
     */
    public function findAllPic($albumId){

        $album = $this->albumDAO->findById($albumId);

        $req = "select pic_id, pic_url, pic_title from alb_picture where alb_id=?";
        $result = $this->getDb()->fetchAll($req, array($albumId));
        $pictures = array();
        foreach ($result as $row){
            $picId = $row['pic_id'];
            $picture = $this->buildObject($row);
            $picture->setAlbum($album);
            $pictures[$picId] = $picture;
        }
        return $pictures;
    }

    public function findById($pictureId){
        $req = " select * from alb_picture where pic_id=? ";
        $result = $this->getDb()->fetchAssoc($req, array($pictureId));
        return $this->buildObject($result);
    }


    public function picForSlider($albumId){
        $req = "select pic_url from alb_picture where alb_id=?";
        $result = $this->getDb()->fetchAll($req, array($albumId));
        $pictures = array();
        foreach ($result as $row => $value){
            foreach ($value as $url){
             array_push($pictures,$url);
            }
        }
        return $pictures;
    }

    public function savePic(Picture $picture){
        $pictureData = array(
            'pic_url'=>$picture->getUrl(),
            'pic_title'=>$picture->getTitle(),
            'alb_id'=>$picture->getAlbum()->getId()
        );
        $this->getDb()->insert('alb_picture',$pictureData);
        $id = $this->getDb()->lastInsertId();
        $picture->setId($id);
    }

    public function deleteAllPic($albumId){
        $this->getDb()->delete('alb_picture',array('alb_id' => $albumId));
    }

    public function deletePicture($pictureId){
        $this->getDb()->delete('alb_picture',array('pic_id' => $pictureId));
    }

    protected function buildObject($row)
    {
        $picture = new Picture();
        $picture->setId($row['pic_id']);
        $picture->setUrl($row['pic_url']);
        $picture->setTitle($row['pic_title']);

        if (array_key_exists('alb_id', $row)){
            $albumId = $row['alb_id'];
            $album = $this->albumDAO->findById($albumId);
            $picture->setAlbum($album);
        }
        return $picture;
    }
}