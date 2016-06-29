<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeAction extends PermitAction
{
    public function dispatch(Request $request, Response $response, $args)
    {
        date_default_timezone_set("Asia/Taipei");
        $begintime=strtotime("2016-6-24 04:00:00");//記得用英國時間!!!!!!
        $nowtime=date("y/m/d h:i:s A");
        $endtime=strtotime(date("Y-m-d h:i:s"));
        $state=floor(($endtime-$begintime)/(15*60));
        $mins=floor((15*60-(($endtime-$begintime)%(15*60)))/60);
        $secs=(15*60-(($endtime-$begintime)%(15*60)))%60;
        $rtime="$mins".":"."$secs";

        $materialnum=8;
        $productnum=8;
        $account = $this->account;
        $teamname="";
        $team_id=0;
        $teamquery = $this->sql['default']->query("SELECT TeamNUM, TeamName , TeamAccount , MoneyCount , Productivity FROM team WHERE Admin <> 1 Order by MoneyCount DESC , Productivity DESC");
        if (!$teamquery)$teamarr=[];
        else {$teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
        foreach ($teamarr as $key => $value) {
            if ($account==$value['TeamAccount']) {
                $team_id=$value['TeamNUM'];
                $teamname=$value['TeamName'];
            }
        }
        }
        
        $adminquery = $this->sql['default']->query("SELECT TeamNUM, TeamName , Admin FROM team WHERE TeamAccount = '$account' ");
        $adminarr = $adminquery -> fetchAll(\PDO::FETCH_ASSOC);
        if (!$adminarr)$is_admin=0;
        else {
            $teamname=$adminarr[0]['TeamName'];
            $is_admin=$adminarr[0]['Admin'];
        }

        if ($teamname==""){
            return $response->withRedirect('/offersys/test/public/login');            
        }

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
        else {$buyinarr = $buyinquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $materialnum ; $i++) {
            $item_id=$i+1;
            $buyarr[$i]['inventory']=$buyinarr[0]["Product$item_id"];
        }
        }

        $sellinquery = $this->sql['default']->query("SELECT * FROM teamsell WHERE TeamNUM = '$team_id' ");
        if (!$sellinquery)$sellinarr=[];
        else {$sellinarr = $sellinquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $productnum ; $i++) { 
            $item_id=$i+1;
            $sellarr[$i]['inventory']=$sellinarr[0]["Goods$item_id"];
            $proarr[$i]['inventory']=$sellinarr[0]["Goods$item_id"];
        }
        }

        $buyorquery = $this->sql['default']->query("SELECT * FROM buyorder WHERE TeamNUM = '$team_id' ");
        if (!$buyorquery)$buyorarr=[];
        else {$buyorarr = $buyorquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $materialnum ; $i++) {
            $item_id=$i+1;
            $buyarr[$i]['ordered']=$buyorarr[0]["Product$item_id"];
        }
        }

        $sellorquery = $this->sql['default']->query("SELECT * FROM sellorder WHERE TeamNUM = '$team_id' ");
        if (!$sellorquery)$sellorarr=[];
        else {$sellorarr = $sellorquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $productnum ; $i++) { 
            $item_id=$i+1;
            $sellarr[$i]['ordered']=$sellorarr[0]["Goods$item_id"];
        }
        }

        $produceorquery = $this->sql['default']->query("SELECT * FROM produceorder WHERE TeamNUM = '$team_id' ");
        if (!$produceorquery)$produceorarr=[];
        else {$produceorarr = $produceorquery -> fetchAll(\PDO::FETCH_ASSOC);
        for ($i=0; $i < $productnum ; $i++) { 
            $item_id=$i+1;
            $proarr[$i]['ordered']=$produceorarr[0]["Producing$item_id"];
        }
        }

        $teaminfoquery = $this->sql['default']->query("SELECT * FROM team As a LEFT OUTER JOIN teambuy As b ON a.TeamNUM=b.TeamNUM LEFT OUTER JOIN teamsell As c ON a.TeamNUM=c.TeamNUM WHERE a.admin <> 1 ");
        if (!$teaminfoquery)$teaminfoarr=[];
        else $teaminfoarr = $teaminfoquery -> fetchAll(\PDO::FETCH_ASSOC);
        
        $params = [
            'name' => $teamname,
            'admin' => $is_admin,
            'teamstatus' => $teamarr,
            'buyrecords' => $buyarr,
            'productrecords' => $proarr,
            'sellrecords' => $sellarr,
            'teaminforecords' => $teaminfoarr,
            'NowTime' => $nowtime,
            'RTime' => $rtime,
            'NowState' => $state
        ];

        //$this->logger->info($begintime);
        //$this->logger->info($teamname);
        $this->view->render($response, 'home.twig', $params);
        return $response;
    }

    public function updatebuy(Request $request, Response $response, $args)
    {
        $account = $this->account;
        $body = $request->getParsedBody();
        $begintime=strtotime("2016/7/6 14:00:00");
        $materialnum=8;
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

        //$this->logger->debug("order:\n",$materialorder);

        return $response->withRedirect('/offersys/test/public');

    }

    public function updatesell(Request $request, Response $response, $args)
    {
        $account = $this->account;
        $body = $request->getParsedBody();
        $begintime=strtotime("2016/7/6 14:00:00");
        $productnum=8;
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

        //$this->logger->debug("order:\n",$productorder);

        return $response->withRedirect('/offersys/test/public');

    }

    public function updateproduce(Request $request, Response $response, $args)
    {
        $account = $this->account;
        $body = $request->getParsedBody();
        $begintime=strtotime("2016/7/6 14:00:00");
        $productnum=8;
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

        //$this->logger->debug("order:\n",$produceorder);

        return $response->withRedirect('/offersys/test/public');

    }

    public function updateteam(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $begintime=time();
        $max_team_r = $this->sql['default']->query("SELECT MAX(TeamNUM) FROM team ");
        $max_team_rs = $max_team_r->fetch();
        $max_team_id = $max_team_rs[0];
        for ($i=1; $i<=$max_team_id ; $i++) { 
            $teamdataquery = $this->sql['default']->query("SELECT * FROM team WHERE TeamNUM = '$i' ");
            if (!$teamdataquery)break;
            $teamdataarr = $teamdataquery -> fetchAll(\PDO::FETCH_ASSOC);
            $buydataquery = $this->sql['default']->query("SELECT * FROM teambuy WHERE TeamNUM = '$i' ");
            if (!$buydataquery)break;
            $buydataarr = $buydataquery -> fetchAll(\PDO::FETCH_ASSOC);
            $selldataquery = $this->sql['default']->query("SELECT * FROM teamsell WHERE TeamNUM = '$i' ");
            if (!$selldataquery)break;
            $selldataarr = $selldataquery -> fetchAll(\PDO::FETCH_ASSOC);

            $teamarg[0]=$body['team'][$i][0]+$teamdataarr[0]['MoneyCount'];
            $teamarg[1]=$body['team'][$i][1]+$teamdataarr[0]['Productivity'];
            $teamarg[2]=$body['team'][$i][2]+$teamdataarr[0]['BuyBUFF'];
            $teamarg[3]=$body['team'][$i][3]+$teamdataarr[0]['SellBUFF'];

            for ($j=0; $j <8 ; $j++) { 
                $item_id=$j+1;
                $buyarg[$j]=$body['buy'][$i][$j]+$buydataarr[0]["Product$item_id"];
                $sellarg[$j]=$body['sell'][$i][$j]+$selldataarr[0]["Goods$item_id"];
            }

            $teamquery = $this->sql['default']->prepare("UPDATE team SET MoneyCount=$teamarg[0] , Productivity=$teamarg[1] , BuyBUFF=$teamarg[2] , SellBUFF=$teamarg[3] WHERE TeamNUM = '$i' ");
            $teamquery->execute();
            $teambuyquery = $this->sql['default']->prepare("UPDATE teambuy SET Product1=$buyarg[0] , Product2=$buyarg[1] , Product3=$buyarg[2] , Product4=$buyarg[3] , Product5=$buyarg[4] , Product6=$buyarg[5] , Product7=$buyarg[6] , Product8=$buyarg[7] WHERE TeamNUM = '$i' ");
            $teambuyquery->execute();
            $teamsellquery = $this->sql['default']->prepare("UPDATE teamsell SET Goods1=$sellarg[0] , Goods2=$sellarg[1] , Goods3=$sellarg[2] , Goods4=$sellarg[3] , Goods5=$sellarg[4] , Goods6=$sellarg[5] , Goods7=$sellarg[6] , Goods8=$sellarg[7] WHERE TeamNUM = '$i' ");
            $teamsellquery->execute();
            
        }
        $endtime=time();

        // $this->logger->debug("team",$teamarg);
        // $this->logger->debug("buy",$buyarg);
        // $this->logger->debug("sell",$sellarg);
        $this->logger->info($begintime);
        $this->logger->info($endtime);

        return $response->withRedirect('/offersys/test/public');

    }
    
    
}
