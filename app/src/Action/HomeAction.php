<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeAction extends PermitAction
{
    public function dispatch(Request $request, Response $response, $args)
    {
        $begintime=strtotime("2016/7/6 14:00:00");
        $materialnum=8;
        $productnum=8;
        $account = $this->account;
        $teamquery = $this->sql['default']->query("SELECT TeamNUM, TeamName , TeamAccount , MoneyCount , Productivity FROM team WHERE Admin <> 1 Order by MoneyCount DESC ");
        if (!$teamquery)$teamarr=[];
        else $teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
        foreach ($teamarr as $key => $value) {
            if ($account==$value['TeamAccount']) {
                $team_id=$value['TeamNUM'];
                $teamname=$value['TeamName'];
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

        $buyorquery = $this->sql['default']->query("SELECT * FROM buyorder WHERE TeamNUM = '$team_id' ");
        if (!$buyorquery)$buyorarr=[];
        else $buyorarr = $buyorquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $materialnum ; $i++) {
            $item_id=$i+1;
            $buyarr[$i]['ordered']=$buyorarr[0]["Product$item_id"];
        }

        $sellorquery = $this->sql['default']->query("SELECT * FROM sellorder WHERE TeamNUM = '$team_id' ");
        if (!$sellorquery)$sellorarr=[];
        else $sellorarr = $sellorquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $productnum ; $i++) { 
            $item_id=$i+1;
            $sellarr[$i]['ordered']=$sellorarr[0]["Goods$item_id"];
        }

        $produceorquery = $this->sql['default']->query("SELECT * FROM produceorder WHERE TeamNUM = '$team_id' ");
        if (!$produceorquery)$produceorarr=[];
        else $produceorarr = $produceorquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $productnum ; $i++) { 
            $item_id=$i+1;
            $proarr[$i]['ordered']=$produceorarr[0]["Producing$item_id"];
        }

        $params = [
            'name' => $teamname,
            'admin' => $is_admin,
            'teamstatus' => $teamarr,
            'buyrecords' => $buyarr,
            'productrecords' => $proarr,
            'sellrecords' => $sellarr
        ];

        //$this->logger->info($begintime);
        $this->view->render($response, 'home.twig', $params);
        return $response;
    }

    public function updatebuy(Request $request, Response $response, $args)
    {
        $account = $this->account;
        $body = $request->getParsedBody();
        $begintime=strtotime("2016/7/6 14:00:00");
        $materialnum=1;
        for ($i=0; $i <8 ; $i++) { 
            $materialorder[$i]=0;
        }
        for ($i=0; $i < $materialnum ; $i++) { 
            $materialorder[$i]=$body['buy'][$i];
        }


        $teamquery = $this->sql['default']->query("SELECT TeamNUM FROM team WHERE TeamAccount = '$account' ");
        if (!$teamquery)$teamarr=[];
        else $teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
        $team_id=$teamarr[0]['TeamNUM'];

        $noworderquery = $this->sql['default']->query("SELECT * FROM buyorder WHERE TeamNUM = '$team_id' ");
        if (!$noworderquery)$noworderarr=[];
        else $noworderarr = $noworderquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i <8 ; $i++) { 
            $item_id=$i+1;
            $materialorder[$i]=$materialorder[$i]+$noworderarr[0]["Product$item_id"];
        }

        $buyorderquery = $this->sql['default']->prepare("UPDATE buyorder SET Product1 = $materialorder[0] , Product2 = $materialorder[1] , Product3 = $materialorder[2] , Product4 = $materialorder[3] , Product5 = $materialorder[4] , Product6 = $materialorder[5] , Product7 = $materialorder[6] , Product8 = $materialorder[7] WHERE TeamNUM = '$team_id' ");
        $buyorderquery->execute();

        $this->logger->debug("order:\n",$materialorder);

        return $response->withRedirect('/');

    }

    public function updatesell(Request $request, Response $response, $args)
    {
        $account = $this->account;
        $body = $request->getParsedBody();
        $begintime=strtotime("2016/7/6 14:00:00");
        $productnum=1;
        for ($i=0; $i <8 ; $i++) { 
            $productorder[$i]=0;
        }
        for ($i=0; $i < $productnum ; $i++) { 
            $productorder[$i]=$body['sell'][$i];
        }


        $teamquery = $this->sql['default']->query("SELECT TeamNUM FROM team WHERE TeamAccount = '$account' ");
        if (!$teamquery)$teamarr=[];
        else $teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
        $team_id=$teamarr[0]['TeamNUM'];

        $noworderquery = $this->sql['default']->query("SELECT * FROM sellorder WHERE TeamNUM = '$team_id' ");
        if (!$noworderquery)$noworderarr=[];
        else $noworderarr = $noworderquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i <8 ; $i++) { 
            $item_id=$i+1;
            $productorder[$i]=$productorder[$i]+$noworderarr[0]["Goods$item_id"];
        }

        $sellorderquery = $this->sql['default']->prepare("UPDATE sellorder SET Goods1 = $productorder[0] , Goods2 = $productorder[1] , Goods3 = $productorder[2] , Goods4 = $productorder[3] , Goods5 = $productorder[4] , Goods6 = $productorder[5] , Goods7 = $productorder[6] , Goods8 = $productorder[7] WHERE TeamNUM = '$team_id' ");
        $sellorderquery->execute();

        $this->logger->debug("order:\n",$productorder);

        return $response->withRedirect('/');

    }

    public function updateproduce(Request $request, Response $response, $args)
    {
        $account = $this->account;
        $body = $request->getParsedBody();
        $begintime=strtotime("2016/7/6 14:00:00");
        $productnum=1;
        for ($i=0; $i <8 ; $i++) { 
            $produceorder[$i]=0;
        }
        for ($i=0; $i < $productnum ; $i++) { 
            $produceorder[$i]=$body['produce'][$i];
        }


        $teamquery = $this->sql['default']->query("SELECT TeamNUM FROM team WHERE TeamAccount = '$account' ");
        if (!$teamquery)$teamarr=[];
        else $teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
        $team_id=$teamarr[0]['TeamNUM'];

        $noworderquery = $this->sql['default']->query("SELECT * FROM produceorder WHERE TeamNUM = '$team_id' ");
        if (!$noworderquery)$noworderarr=[];
        else $noworderarr = $noworderquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i <8 ; $i++) { 
            $item_id=$i+1;
            $produceorder[$i]=$produceorder[$i]+$noworderarr[0]["Producing$item_id"];
        }

        $produceorderquery = $this->sql['default']->prepare("UPDATE produceorder SET Producing1 = $produceorder[0] , Producing2 = $produceorder[1] , Producing3 = $produceorder[2] , Producing4 = $produceorder[3] , Producing5 = $produceorder[4] , Producing6 = $produceorder[5] , Producing7 = $produceorder[6] , Producing8 = $produceorder[7] WHERE TeamNUM = '$team_id' ");
        $produceorderquery->execute();

        $this->logger->debug("order:\n",$produceorder);

        return $response->withRedirect('/');

    }

    public function buy(Request $request, Response $response, $args)
    {

    }

    public function produce(Request $request, Response $response, $args)
    {

    }

    public function sell(Request $request, Response $response, $args)
    {

    }
    
}
