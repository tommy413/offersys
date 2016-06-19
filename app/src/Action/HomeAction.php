<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeAction extends PermitAction
{

    public function dispatch(Request $request, Response $response, $args)
    {
        
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

        $this->view->render($response, 'home.twig', $params);
        return $response;
    }

    
}
