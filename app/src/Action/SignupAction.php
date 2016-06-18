<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SignupAction extends BaseAction
{

    public function dispatch(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'signup.twig');
        return $response;
    }

    public function signup(Request $request, Response $response, $args)
    {
    	$this->logger->info("Signup action called");
        $body = $request->getParsedBody();

    	$team= $body['team'];
    	$account = $body['account'];
    	$password = $body['password'];

        $passhash = password_hash($password, PASSWORD_DEFAULT);
        $max_team_r = $this->sql['default']->query("SELECT MAX(TeamNUM) FROM team ");
        $max_team_rs = $max_team_r->fetch();
        $max_team_id = $max_team_rs[0];
        $inserting_id=0;
        if ($max_team_id >= 0)$inserting_id=$max_team_id+1;

        $signupquery = $this->sql['default']->prepare("INSERT INTO team(TeamNUM , TeamName , TeamAccount , TeamPassword , Admin) VALUES ($inserting_id , '$team' , '$account' , '$passhash' , 0)");
        $signupquery->execute();
        
        /**
    	
    		TODO:
    		- save into db
    	
    	 */
    	
        return $response->withRedirect('/');
    }
}
