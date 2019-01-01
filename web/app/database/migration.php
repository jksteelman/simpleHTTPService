<?php

namespace app\database;

/**
 * Simple database schema holder.
 * @usage: \app\database\migration::up();
 * @usage: \app\database\migration::down();
 *
 * Class migration
 * @package app\database
 */
class migration
{

    /**
     * Creates the schema group and tables
     * @return bool
     */
    static public function up() {
        $conn = connection::conn();
        $good = 0;
        $good += $conn->prepare(self::getCreateSchema())->execute();
        $good += $conn->prepare(self::getCreateTableRecipe())->execute();
        $good += $conn->prepare(self::getCreateTableRating())->execute();
        return 3 === $good;
    }

    /**
     * Reverses up()
     * @return bool
     */
    static public function down() {
        $conn = connection::conn();
        $good = 0;
        /** @lang PostgreSQL */
        $good += $conn->prepare( "DROP TABLE IF EXISTS models.recipe;")->execute();
        $good += $conn->prepare( "DROP TABLE IF EXISTS models.rating;")->execute();
        $good += $conn->prepare( "DROP SCHEMA IF EXISTS models;")->execute();
        return 3 === $good;
    }

    /**
     * @return string
     */
    static public function getCreateSchema() {
        /** @lang PostgreSQL */
        return  "CREATE SCHEMA models";
    }

    /**
     * @return string
     */
    static public function getCreateTableRecipe() {
        /** @lang PostgreSQL */
        return
            "CREATE TABLE models.recipe
            (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255),
                prep_time INT DEFAULT 0,
                difficulty INT DEFAULT 1,
                vegetarian BOOLEAN DEFAULT FALSE
            ); ";
    }
    /**
     * @return string
     */
    static public function getCreateTableRating() {
        /** @lang PostgreSQL */
        return
            "CREATE TABLE models.rating
            (
                id SERIAL PRIMARY KEY,
                recipie_id INT,
                rating INT
            );";
    }
}