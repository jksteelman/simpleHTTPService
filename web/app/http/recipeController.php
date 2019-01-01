<?php

namespace app\http;

use app\model\rating;
use app\model\recipe;
use app\traits\admin_key;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Some good, old fashioned BREAD
 * Class recipeController
 * @package app\http
 */
class recipeController
{
    use admin_key;

    /**
     * The instructions call for "list", but that's a reserved keyword so I'm using index instead
     */
    static public function index(ServerRequestInterface $request, ResponseInterface $response) {
        $recipe = new recipe();
        $response->getBody()->write(\json_encode($recipe::all()));
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    /**
     * Select one record and return json.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    static public function get(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $recipe = new recipe();
        $recipe->get($args['id']);
        $recipe->rating = (string) $recipe->rating();
        $response->getBody()->write($recipe);
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return ResponseInterface
     */
    static public function update(ServerRequestInterface $request, ResponseInterface $response, $args) {
        if(self::is_valid_admin_key($request) === false) {
            $response->getBody()->write(\json_encode(['success' => false, 'error' => 'Invalid auth key. Please provide a valid key as _auth_key']));
            return $response;
        }
        $recipe = new recipe();
        $recipe->get($args['id']);
        foreach($recipe as $key => $value) {
            if(isset($request->getParsedBody()[$key])) {
                $recipe->{$key} = $request->getParsedBody()[$key];
            }
        }
        $recipe->save();
        $response->getBody()->write($recipe);
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return ResponseInterface
     */
    static public function create(ServerRequestInterface $request, ResponseInterface $response, $args) {
        if(self::is_valid_admin_key($request) === false) {
            $response->getBody()->write(\json_encode(['success' => false, 'error' => 'Invalid auth key. Please provide a valid key as _auth_key']));
            return $response;
        }
        $recipe = new recipe();
        $fillable_properties = ['name', 'prep_time', 'difficulty', 'vegetarian'];
        foreach($fillable_properties as $key) {
            if(isset($request->getParsedBody()[$key])) {
                $recipe->{$key} = $request->getParsedBody()[$key];
            }
        }
        $recipe->save();
        $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write($recipe);
        return $response;
    }

    /**
     * Select one record and return json.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    static public function delete(ServerRequestInterface $request, ResponseInterface $response, $args) {
        if(self::is_valid_admin_key($request) === false) {
            $response->getBody()->write(\json_encode([
                'success' => false,
                'error' => 'Invalid auth key. Please provide a valid key as _auth_key',
                'provided' => $request->getParsedBody(),
            ]));
            return $response;
        }
        $recipe = new recipe();
        $recipe->get($args['id']);
        if($recipe->id) {
            $recipe->delete();
            $response->getBody()->write(\json_encode(['success' => true]));
        } else {
            $response->getBody()->write(\json_encode(['success' => false, 'error' => 'that recipe does not exist']));
        }
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return ResponseInterface
     */
    static public function rate(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $rating = new rating();
        $rating->recipie_id = (int) $args['id'];
        $rating->rating = $request->getParsedBody()['rating'];
        $rating->save();
        return self::get($request, $response, $args);
    }
}