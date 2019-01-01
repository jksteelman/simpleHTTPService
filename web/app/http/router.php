<?php

namespace app\http;

use League\Container\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
use League\Route\RouteCollection;

class router
{

    static private $routes;
    static public function METHOD($path, $function, $method = "GET") {
        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'function' => $function,
        ];
    }

    /**
     * Shorthand for method:get
     * @param $path
     * @param $function
     */
    static public function GET($path, $function) {
        self::METHOD($path, $function, "GET");
    }

    /**
     * Shorthand for method:post
     * @param $path
     * @param $function
     */
    static public function POST($path, $function) {
        self::METHOD($path, $function, "POST");
    }

    /**
     * Shorthand for method:put
     * @param $path
     * @param $function
     */
    static public function PUT($path, $function) {
        self::METHOD($path, $function, "PUT");
    }

    /**
     * Shorthand for method:delete
     * @param $path
     * @param $function
     */
    static public function DELETE($path, $function) {
        self::METHOD($path, $function, "DELETE");
    }


    /**
     * Capture current HTTP request,
     * Build route collection based on injected route requests
     * Call current route.
     * @todo extend the functionality to support conditions, verbs, groups, strategies, and middleware
     */
    static public function resolve() {
        $container = new Container;
        $container->share('response', Response::class);
        $container->share('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });
        $container->share('emitter', SapiEmitter::class);
        $route = new RouteCollection($container);
        foreach(self::$routes as $array) {
            $route->map($array['method'], $array['path'], $array['function']);
        }
        $response = $route->dispatch($container->get('request'), $container->get('response'));
        $container->get('emitter')->emit($response);
    }
}