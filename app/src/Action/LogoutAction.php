<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class LogoutAction extends BaseAction
{

    public function logout(Request $request, Response $response, $args)
    {
        session_unset();
        session_destroy();
        return $response->withRedirect('login');
    }
}
