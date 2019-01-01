<?php

namespace app\http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Capturing our routes.
 *
 * Class routes
 * @package app\http
 */
class routes
{

    /**
     * Define a route using a closure function or reference a controller.
     */
    static public function register() {
        router::GET('/', function (ServerRequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('
                <h1>Recipe API</h1>
                <p>You\'re probably looking for the <a href="/recipes">recipes</a> list.</p>
                <p><a href="/migrate/up">Migrate Up</a> (creates a database schema if one does not exist)</p>
                <p><a href="/migrate/down">Migrate Down</a> (destroys the database data and schmea)</p>
                <p><a href="/migrate/seed">Migrate Seed</a> (create some random recipes and ratings)</p>
            ');
            return $response;
        });
        router::get('migrate/up', function(ServerRequestInterface $request, ResponseInterface $response) {
            if(\app\database\migration::up()) {
                $response->getBody()->write('Migrated Up Successfully');
            } else {
                $response->getBody()->write('Migrated Up Failed');
            }
            return $response;
        });
        router::get('migrate/down', function(ServerRequestInterface $request, ResponseInterface $response) {
            if(\app\database\migration::down()) {
                $response->getBody()->write('Migrated down Successfully');
            } else {
                $response->getBody()->write('Migrated down Failed');
            }
            return $response;
        });
        router::get('migrate/seed', function(ServerRequestInterface $request, ResponseInterface $response) {
            if(\app\database\seeder::seed()) {
                $response->getBody()->write('Seed Successful');
            } else {
                $response->getBody()->write('Seed Failed');
            }
            return $response;
        });
        router::GET('/recipes',             '\app\http\recipeController::index');
        router::GET('/recipes/{id}',        '\app\http\recipeController::get');
        router::POST('/recipes/{id}',       '\app\http\recipeController::update');
        router::DELETE('/recipes/{id}',     '\app\http\recipeController::delete');
        router::POST('/recipes',            '\app\http\recipeController::create');
        router::POST('/recipes/{id}/rating','\app\http\recipeController::rate');
    }
}