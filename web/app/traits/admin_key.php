<?php

namespace app\traits;

use Psr\Http\Message\ServerRequestInterface;


trait admin_key
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    static public function is_valid_admin_key(ServerRequestInterface $request) {
        if(
            isset($request->getParsedBody()['_auth_key'])
            AND $request->getParsedBody()['_auth_key']
            AND getenv('ADMIN_KEY') === $request->getParsedBody()['_auth_key']
        ) {
            return true;
        } else if(
            $request->hasHeader('auth-key')
            AND $request->getHeaderLine('auth-key') === getenv('ADMIN_KEY')
        ) {
            return true;
        } else {
            return false;
        }
    }
}