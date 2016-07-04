<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class BackAction extends BaseAction
{
    public function dispatch(Request $request, Response $response, $args)
    {
        $teaminfoquery = $this->sql['default']->query("SELECT * FROM team As a LEFT OUTER JOIN teambuy As b ON a.TeamNUM=b.TeamNUM LEFT OUTER JOIN teamsell As c ON a.TeamNUM=c.TeamNUM WHERE a.admin <> 1 ");
        if (!$teaminfoquery)$teaminfoarr=[];
        else $teaminfoarr = $teaminfoquery -> fetchAll(\PDO::FETCH_ASSOC);

        $buyinfoquery = $this->sql['default']->query("SELECT * FROM buyprice Order By ProductNUM ASC");
        if (!$buyinfoquery)$buyinfoarr=[];
        else $buyinfoarr = $buyinfoquery -> fetchAll(\PDO::FETCH_ASSOC);

        $produceinfoquery = $this->sql['default']->query("SELECT * FROM producing Order By GoodsNUM ASC");
        if (!$produceinfoquery)$produceinfoarr=[];
        else $produceinfoarr = $produceinfoquery -> fetchAll(\PDO::FETCH_ASSOC);

        $sellinfoquery = $this->sql['default']->query("SELECT * FROM sellprice Order By GoodsNUM ASC");
        if (!$sellinfoquery)$sellinfoarr=[];
        else $sellinfoarr = $sellinfoquery -> fetchAll(\PDO::FETCH_ASSOC);

        $params = [
            'teaminforecords' => $teaminfoarr,
            'buyinforecords' => $buyinfoarr,
            'produceinforecords' => $produceinfoarr,
            'sellinforecords' => $sellinfoarr
        ];

        $this->view->render($response, 'back.twig' , $params);
        return $response;
    }

    public function run_dispatch(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'run.twig');
        return $response;
    }

    public function updateprice(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();

        for ($i=1; $i <= 8 ; $i++) { 

            $buydata=$body['buyprice'][$i];
            $producedata=$body['produce'][$i];
            $selldata=$body['sellprice'][$i];

            $updatequery = $this->sql['default']->prepare("UPDATE buyprice SET ProductName=$buydata[0],Price1=$buydata[1],Price2=$buydata[2],Price3=$buydata[3],Price4=$buydata[4],Price5=$buydata[5] WHERE ProductNUM = '$i' ");
            $updatequery->execute();
            $updatequery = $this->sql['default']->prepare("UPDATE producing SET GoodsName=$producedata[0], Product1Needed=$producedata[1] , Product2Needed=$producedata[2] , Product3Needed=$producedata[3] , Product4Needed=$producedata[4] , Product5Needed=$producedata[5] , Product6Needed=$producedata[6] , Product7Needed=$producedata[7] , Product8Needed=$producedata[8] WHERE GoodsNUM = '$i' ");
            $updatequery->execute();
            $updatequery = $this->sql['default']->prepare("UPDATE sellprice SET GoodsName=$selldata[0], Price1=$selldata[1] , Price2=$selldata[2], Price3=$selldata[3], Price4=$selldata[4], Price5=$selldata[5] WHERE GoodsNUM = '$i' ");
            $updatequery->execute();
        }

        return $response->withRedirect('/offersys/test/public/back');
        
    }

    public function init(Request $request, Response $response, $args)
    {
        $teamquery= $this->sql['default']->prepare("SELECT MAX(TeamNUM) from team ");
        $teamquery->execute();
        $teamarr = $teamquery -> fetch();
        $teamcount = $teamarr[0];

        for ($i=1; $i <=$teamcount ; $i++) { 
            $updatequery = $this->sql['default']->prepare("DELETE FROM buyorder WHERE TeamNUM = '$i' ");
            $updatequery->execute();
            $updatequery = $this->sql['default']->prepare("INSERT INTO buyorder(TeamNUM) VALUES ('$i') ");
            $updatequery->execute();
            $updatequery = $this->sql['default']->prepare("DELETE FROM sellorder WHERE TeamNUM = '$i' ");
            $updatequery->execute();
            $updatequery = $this->sql['default']->prepare("INSERT INTO sellorder(TeamNUM) VALUES ('$i') ");
            $updatequery->execute();
            $updatequery = $this->sql['default']->prepare("DELETE FROM produceorder WHERE TeamNUM = '$i' ");
            $updatequery->execute();
            $updatequery = $this->sql['default']->prepare("INSERT INTO produceorder(TeamNUM) VALUES ('$i') ");
            $updatequery->execute();
        }
        return $response->withRedirect('/offersys/test/public/run');
    }

    public function deleteteam(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $deleteteam=$body['delete_teamname'];

        $idquery = $this->sql['default']->query("SELECT TeamNUM FROM team WHERE TeamName = '$deleteteam' ");
        if (!$idquery)return $response->withRedirect('/offersys/test/public/back');
        else {$idarr = $idquery -> fetch();
        $deleteid = $idarr[0];}

        $updatequery = $this->sql['default']->prepare("DELETE FROM team WHERE TeamNUM = '$deleteid' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("DELETE FROM teambuy WHERE TeamNUM = '$deleteid' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("DELETE FROM teamsell WHERE TeamNUM = '$deleteid' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("DELETE FROM buyorder WHERE TeamNUM = '$deleteid' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("DELETE FROM produceorder WHERE TeamNUM = '$deleteid' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("DELETE FROM sellorder WHERE TeamNUM = '$deleteid' ");
        $updatequery->execute();

        $teamquery= $this->sql['default']->prepare("SELECT MAX(TeamNUM) from team ");
        $teamquery->execute();
        $teamarr = $teamquery -> fetch();
        $teamcount = $teamarr[0];
        if ($teamcount>$deleteid){
        $updatequery = $this->sql['default']->prepare("UPDATE team SET TeamNUM='$deleteid' WHERE TeamNUM = '$teamcount' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("UPDATE teambuy SET TeamNUM='$deleteid' WHERE TeamNUM = '$teamcount' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("UPDATE teamsell SET TeamNUM='$deleteid' WHERE TeamNUM = '$teamcount' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("UPDATE buyorder SET TeamNUM='$deleteid' WHERE TeamNUM = '$teamcount' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("UPDATE sellorder SET TeamNUM='$deleteid' WHERE TeamNUM = '$teamcount' ");
        $updatequery->execute();
        $updatequery = $this->sql['default']->prepare("UPDATE produceorder SET TeamNUM='$deleteid' WHERE TeamNUM = '$teamcount' ");
        $updatequery->execute();
        }

        return $response->withRedirect('/offersys/test/public/back');
    }

}
