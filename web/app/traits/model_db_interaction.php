<?php

namespace app\traits;

use \app\database\connection;

trait model_db_interaction {

    static public function getTableName() {
        $array = explode('\\',  self::class);
        return "hellofresh.models.". array_pop($array);
    }

    /**
     * @param array $row
     * @param \stdClass $instance
     * @return \stdClass
     */
    static public function fill($row, $instance) {
        foreach($row as $key => $value) {
            $instance->$key = $value;
        }
        return $instance;
    }

    public function get($id) {
        $statement = connection::conn()->prepare("SELECT * FROM ".self::getTableName()." WHERE id = :id");
        $statement->bindValue("id", $id);
        $statement->execute();
        if($statement->rowCount() !== 1) {
            return $this;
        }
        $row = $statement->fetchAll()[0];
        return self::fill($row, $this);
    }

    static public function all($limit = 0, $offset = 0) {
        $sql = "SELECT * FROM ". self::getTableName();
        if($limit) {
            $sql .= " LIMIT ".$limit." OFFSET " . $offset;
        }
        $statement = connection::conn()->prepare($sql);
        $statement->execute();
        $return = [];
        foreach($statement->fetchAll() as $row) {
            $className = self::class;
            $obj = new $className();
            $return[] = self::fill($row, $obj);
        }
        return $return;
    }

    public function save() {
        $properties = [];
        foreach($this as $key => $value) {
            $properties[$key] = $value;
        }

        if(isset($this->id)) {
            connection::conn()->update(self::getTableName(), $properties, array('id' => $this->id));
        } else {
            connection::conn()->insert(self::getTableName(), $properties);
        }
    }

    public function delete() {
        connection::conn()->delete(self::getTableName(), ['id' => $this->id]);
    }

    public function __toString()
    {
        return \json_encode($this);
    }
}