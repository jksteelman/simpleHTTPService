<?php

namespace app\model;

use app\database\connection;
use app\traits\model_db_interaction;


/**
 * Class recipe
 * @package app\model
 * @property int $id
 * @property string $name
 * @property int $prep_time
 * @property bool $difficulty
 * @property bool $vegetarian
 */
class recipe
{

    use model_db_interaction;

    function __construct($id = null)
    {
        if($id !== null AND $id > 0) {
            return $this->get($id);
        }
        return $this;
    }

    public function rating() {
        $statement = connection::conn()->prepare("SELECT avg(rating) FROM ".rating::getTableName()." WHERE recipie_id = :id");
        $statement->bindValue("id", $this->id);
        $statement->execute();
        return (int) $statement->fetchAll()[0]['avg'];
    }
}