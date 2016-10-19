<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 03/10/2016
 * Time: 14:41
 */

namespace Portfolio\DAO;

use Doctrine\DBAL\Connection;

abstract class DAO{

    /**
     * Database connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * DAO constructor.
     * @param Connection $db The database connection object
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    protected function getDb(){
        return $this->db;
    }

    protected abstract function buildObject($row);


}