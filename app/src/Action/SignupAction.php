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

    	$office = $body['office'];
    	$group = $body['group'];
    	$account = $body['account'];
    	$password = $body['password'];

        $passhash = password_hash($password, PASSWORD_DEFAULT);
        $ors = $this->sql['default']->query("SELECT OfficeNUM FROM office WHERE Name='".$office."'");
        $grs = $this->sql['default']->query("SELECT GroupNUM FROM groupdata WHERE GroupName='".$group."'");
        $ers = $this->sql['default']->query("SELECT MAX(EmployeeNUM) FROM employee ");
        $oarr = $ors -> fetch(\PDO::FETCH_ASSOC);
        $garr = $grs -> fetch(\PDO::FETCH_ASSOC);
        $earr = $ers -> fetch(\PDO::FETCH_ASSOC);
        $onum = -1;
        $gnum = -1;
        $enum = -1;
        if ($oarr != false)$onum = $oarr['OfficeNUM'];
        if ($garr != false)$gnum = $garr['GroupNUM'];
        if ($earr != false)$enum = $earr['MAX(EmployeeNUM)']+1;
        $organrs = $this->sql['default']->query("SELECT OrganNUM FROM office_organization WHERE OfficeNUM='".$onum."' AND GroupNUM='".$gnum."'");
        $organarr = $organrs -> fetch(\PDO::FETCH_ASSOC);
        $orgnum = -1;
        if ($orgarr != false)$organnum = $organarr['OrganNUM'];

        if ($onum>=0 && $gnum>=0 && $enum>=0 && $orgnum>=0){
            $empquery = $this->sql['default']->prepare("INSERT INTO employee(EmployeeNUM , Name , OrganNUM , Passhash , Admin) VALUES ('".$enum."' , '".$account."' , '".$organnum."' , '".$passhash."' , '')");
            $empquery->execute();
        }
        /**
    	
    		TODO:
    		- save into db
    	
    	 */
    	
        return $response->withRedirect('/');
    }
}
