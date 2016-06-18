<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class LoginAction extends BaseAction
{

    public function dispatch(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'login.twig', [
            'account' => $account,
        ]);
        return $response;
    }

    public function login(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $account = $body['account'];
        $password = $body['password'];
        
        $error = null;

        $pdb = $this->sql['default']->query("SELECT TeamPassword FROM team WHERE TeamAccount='".$account."'");
        $ph = $pdb->fetch(\PDO::FETCH_ASSOC);
        if ($ph==false){
            $error="The account doesn't exist.";
        }
        else {
            $passhash=$ph['TeamPassword'];
        }
        /**
        
        	TODO: save error into $error
        	- check account existence
        	- get passhash from db
        
         */

        //	login success
        if (password_verify($password, $passhash)) {

            $timestamp = time();
            $_SESSION['account'] = $account;
            $_SESSION['timestamp'] = $timestamp;
            $_SESSION['token'] = password_hash("$account:$timestamp", PASSWORD_DEFAULT);

        }
        else {
            $error = "Password incorrect.";
        }

        //	login failed
        if ($error) {
            $this->view->render($response, 'login.twig', [
                'account' => $account,
                'error' => $error,
            ]);
            return $response;
        }
    
        //  login success
        return $response->withRedirect('/');

    }
}
