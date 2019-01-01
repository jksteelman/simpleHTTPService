<?php

namespace app\database;

use app\model\rating;
use \app\model\recipe;

class seeder
{
    static public function makeName() {
        $adj = ['Red', 'Hot', 'Cheesy', 'Bright', 'Happy', 'Leafy'];
        $food = ['Apple', 'Cheese', 'Lemongrass', 'Chocolate', 'Kale', 'Chicken', 'Turkey'];
        $noun = ['Salad', 'Pizza', 'Burger', 'Smoothy', 'Taco', 'Stir-fry'];
        return $adj[array_rand($adj)] . " " . $food[array_rand($food)] . " " . $noun[array_rand($noun)];
    }

    static public function seed() {
        for($i = 0; $i < 50; $i++) {
            $recipe = new recipe();
            $recipe->name = self::makeName();
            $recipe->difficulty = mt_rand(1, 5);
            $recipe->prep_time = mt_rand(1, 6) * 15;
            $recipe->vegetarian = mt_rand(1, 5) === 5 ? "1" : "0";
            $recipe->save();
        }
        $maxRatings = mt_rand(0, 40);
        for($k = 0; $k < $maxRatings; $k++) {
            $rating = new rating();
            $rating->recipie_id = mt_rand(1, 49);
            $rating->rating = mt_rand(1, 5);
            $rating->save();
        }
        return true;
    }
}