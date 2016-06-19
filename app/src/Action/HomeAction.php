<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeAction extends PermitAction
{
    public function dispatch(Request $request, Response $response, $args)
    {
        $begintime=strtotime("2016/7/6 14:00:00");
        $materialnum=1;
        $productnum=1;
        $account = $this->account;
        $teamquery = $this->sql['default']->query("SELECT TeamNUM, TeamName , TeamAccount , MoneyCount , Productivity FROM team WHERE Admin <> 1 Order by MoneyCount DESC ");
        if (!$teamquery)$teamarr=[];
        else $teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
        foreach ($teamarr as $key => $value) {
            if ($account==$value['TeamAccount']) {
                $team_id=$value['TeamNUM'];
            }
        }
        
        $adminquery = $this->sql['default']->query("SELECT Admin FROM team WHERE TeamAccount = '$account' ");
        $adminarr = $adminquery -> fetchAll(\PDO::FETCH_ASSOC);
        if (!$adminarr)$is_admin=0;
        else $is_admin=$adminarr['Admin'];

        $buyquery = $this->sql['default']->query("SELECT * FROM buyprice ");
        if (!$buyquery)$buyarr=[];
        else $buyarr = $buyquery -> fetchAll(\PDO::FETCH_ASSOC);

        $proquery = $this->sql['default']->query("SELECT * FROM producing ");
        if (!$proquery)$proarr=[];
        else $proarr = $proquery -> fetchAll(\PDO::FETCH_ASSOC);

        $sellquery = $this->sql['default']->query("SELECT * FROM sellprice ");
        if (!$sellquery)$sellarr=[];
        else $sellarr = $sellquery -> fetchAll(\PDO::FETCH_ASSOC);

        $buyinquery = $this->sql['default']->query("SELECT * FROM teambuy WHERE TeamNUM = '$team_id' ");
        if (!$buyinquery)$buyinarr=[];
        else $buyinarr = $buyinquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $materialnum ; $i++) {
            $item_id=$i+1;
            $buyarr[$i]['inventory']=$buyinarr[0]["Product$item_id"];

        }

        $sellinquery = $this->sql['default']->query("SELECT * FROM teamsell WHERE TeamNUM = '$team_id' ");
        if (!$sellinquery)$sellinarr=[];
        else $sellinarr = $sellinquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $productnum ; $i++) { 
            $item_id=$i+1;
            $sellarr[$i]['inventory']=$sellinarr[0]["Goods$item_id"];
            $proarr[$i]['inventory']=$sellinarr[0]["Goods$item_id"];
        }

        $params = [
            'admin' => $is_admin,
            'teamstatus' => $teamarr,
            'buyrecords' => $buyarr,
            'productrecords' => $proarr,
            'sellrecords' => $sellarr
        ];

        $this->logger->info($begintime);
        $this->view->render($response, 'home.twig', $params);
        return $response;
    }

    public function updatebuy(Request $request, Response $response, $args)
    {
        $account = $this->account;
        $body = $request->getParsedBody();
        $begintime=strtotime("2016/7/6 14:00:00");
        $materialnum=1;
        $productnum=1;
        for ($i=0; $i < $materialnum ; $i++) { 
            $materialorder[$i]=$body['buy'][$i];
        }

        $teamquery = $this->sql['default']->query("SELECT TeamNUM FROM team WHERE TeamAccount = '$account' ");
        if (!$teamquery)$teamarr=[];
        else $teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
        $team_id=$teamarr[0]['TeamNUM'];

        $buyorderquery = $this->sql['default']->prepare("UPDATE Buyorder 
                                                         SET Product1 = $materialorder[0] , Product2 = $materialorder[1] , Product3 = $materialorder[2] , Product4 = $materialorder[3] ,
                                                             Product5 = $materialorder[4] , Product6 = $materialorder[5] , Product7 = $materialorder[6] , Product8 = $materialorder[7] 
                                                         WHERE TeamNUM = '$team_id' ");
        $buyorderquery->execute();

        return $response->withRedirect('/');

    }

    public function produce(Request $request, Response $response, $args)
    {

    }

    public function sell(Request $request, Response $response, $args)
    {

    }
    
}
