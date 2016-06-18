<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeAction extends PermitAction
{

    public function dispatch(Request $request, Response $response, $args)
    {
        
        $account = $this->account;
        $teamquery = $this->sql['default']->query("SELECT TeamName , MoneyCount , Productivity FROM team WHERE Admin <> 1 Order by MoneyCount DESC ");
        if (!$teamquery)$teamarr=[];
        else $teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
        
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

    public function export(Request $request, Response $response, $args)
    {
    	$body = $request->getParsedBody();
    	$account = $body['account'];
    	$start = $body['start'];       //  YYYY/MM/DD
    	$end = $body['end'];           //  YYYY/MM/DD
        $rec =[] ;

        $equery = $this->sql['default']->query("SELECT * FROM employee WHERE Name='".$account."'");
        $earr = $equery -> fetchAll(\PDO::FETCH_ASSOC);
        $enum=-1;
        if ($earr != false)foreach ($earr as $key => $value) {
            if ($value['Name']==$account)$enum=$value['EmployeeNUM'];
        }

        $pquery = $this->sql['default']->query("SELECT * FROM punchlist ");
        $i=0;
        while ($parr = $pquery -> fetch(\PDO::FETCH_ASSOC)){
            if (strtotime($parr['Time'])<strtotime($start) or strtotime($parr['Time'])>strtotime($end))continue;
            if ($enum!=$parr['EmployeeNUM'])continue;
            $rec[$i]=$parr;
            $i=$i+1;
        }

        $squery = $this->sql['default']->query("SELECT * FROM state ");
        $sarr = $squery -> fetchAll(\PDO::FETCH_ASSOC);
        if ($sarr != false)foreach ($sarr as $skey => $svalue) {
            foreach ($rec as $key => $value) {
                if ($value['StateNUM']==$svalue['StateNUM']){
                    $rec[$key]['state']=$svalue['StateName'];
                    $rec[$key]['In_or_out']=$svalue['In_or_out'];
                }
            }
        }

        $total_hours=0;
        $intime=mktime(0,0,0,0,0,0);
        $inflag=1;
        $outtime=mktime(0,0,0,0,0,0);
        $outflag=0;
        foreach ($rec as $key => $value) {
            if ($value['In_or_out']==1 && $inflag==1){
                $intime=strtotime($value['Time']);
                $inflag=0;
                $outflag=1;
            }
            else if ($value['In_or_out']==0 && $outflag==1){
                $outtime=strtotime($value['Time']);
                $rec['Hours']=round(($outtime-$intime)/60/60);
                $inflag=1;
                $outflag=0;
                $total_hours=$total_hours+$rec['Hours'];
            }
        }

        

        $response = $response->withHeader('Content-type', 'text/x-csv');
        $response = $response->withHeader('Content-Disposition', 'filename=exportFileName.csv');

        $response->write(iconv("big5","UTF8","State,Note,Time,Hours\n"));
        foreach ($rec as $key => $value) {
            $response->write(iconv("big5","UTF8","$value[state],$value[Note],$value[Time],$value[Hours]\n"));
        }        
        $response->write(iconv("big5","UTF8",",,'Total',$total_hours\n"));
    	
    	
        return $response;
    }

    public function punch(Request $request, Response $response, $args)
    {
    	$body = $request->getParsedBody();
    	$account = $body['account'];
    	$state = $body['state'];
    	$note = $body['note'];

        $prs = $this->sql['default']->query("SELECT MAX(ItemNUM) FROM punchlist ");
        $parr = $prs -> fetch(\PDO::FETCH_ASSOC);
        $pnum = $parr['MAX(ItemNUM)']+1;
        
        $ers = $this->sql['default']->query("SELECT EmployeeNUM FROM employee WHERE Name='".$account."'");
        $earr = $ers -> fetch(\PDO::FETCH_ASSOC);
        $enum=-1;
        if ($earr != false)$enum = $earr['EmployeeNUM'];

        $frs = $this->sql['default']->query("SELECT StateNUM FROM state WHERE StateName='".$state."'");
        $farr = $frs -> fetch(\PDO::FETCH_ASSOC);
        $fnum=-1;
        if ($farr != false)$fnum = $farr['StateNUM'];

        if ($enum>=0 && $fnum>=0){
            $values = "( '$pnum', '$enum', '$snum', CURRENT_TIMESTAMP, '$note', '0' )";
            $punquery = $this->sql['default']->prepare("INSERT INTO punchlist(ItemNUM , EmployeeNUM , StateNUM , Time , Note , Disabled) VALUES $values");
            $punquery->execute();
        }
    	
        return $response->withRedirect('/');
    }

    public function getUpdateList(Request $request, Response $response, $args)
    {
        $query = $request->getQueryParams();
        $account = $query['account'];

        $squery = $this->sql['default']->query("SELECT a.EmployeeNUM,a.ItemNUM,b.StateName,a.Note,a.Time,a.Disabled FROM punchlist as a NATURAL JOIN state as b ");
        $sarr = $squery -> fetchAll(\PDO::FETCH_ASSOC);

        $equery = $this->sql['default']->query("SELECT EmployeeNUM,Name FROM employee ");
        $earr = $equery -> fetchAll(\PDO::FETCH_ASSOC);
        $enum=-1;

        $result = [];
        


        if ($earr != false)foreach ($earr as $ekey => $evalue) {
            if ($account==$evalue['Name']){
                $enum=$evalue['EmployeeNUM'];
            }
        }

        if ($sarr != false)foreach ($sarr as $key => $value) {
            if ($enum==$value['EmployeeNUM'] && $value['Disabled']==0){                
                $result[]=[$value['ItemNUM'],$value['StateName'],$value['Note'],$value['Time']];
            }
        }

        foreach ($result as $key => $value) { 
            $date[$key] = $value['Time']; 
            $num[$key] = $value['ItemNUM']; 
        } 
        array_multisort($date,SORT_REGULAR,$num,SORT_REGULAR,$result,SORT_REGULAR);

        return $response->write(json_encode($result));
    }

    public function update(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $account = $body['account'];
        $old_item = $body['old_item'];
        $new_state = $body['new_state'];
        $new_note = $body['new_note'];
        $new_time = $body['new_time'];

        $equery = $this->sql['default']->query("SELECT EmployeeNUM,Name FROM employee ");
        $earr = $equery -> fetchAll(\PDO::FETCH_ASSOC);
        $enum=-1;
        if ($earr != false)foreach ($earr as $ekey => $evalue) {
            if ($account==$evalue['Name']){
                $enum=$evalue['EmployeeNUM'];
            }
        }

        $squery = $this->sql['default']->query("SELECT StateNUM,StateName FROM state ");
        $sarr = $squery -> fetchAll(\PDO::FETCH_ASSOC);
        $snum=-1;
        if ($sarr != false)foreach ($sarr as $skey => $svalue) {
            if ($new_state==$svalue['StateName']){
                $snum=$svalue['StateNUM'];
            }
        }

        $uquery = $this->sql['default']->query("SELECT MAX(ItemNUM) FROM punchlist ");
        $uarr = $uquery -> fetch(\PDO::FETCH_ASSOC);
        $new_item=$uarr['MAX(ItemNUM)']+1;

        $insert_punchlist = "('$new_item' , '$enum' , '$snum' , '$new_note' , STR_TO_DATE('$new_time','%Y/%m/%d %h:%i:%s') , '0' )";
        $insert_update = "( CURRENT_TIMESTAMP , '$enum' , '$old_item' , '$new_item' , '' )";
        
        if ($enum>=0 && $snum>=0){
            $insert_query_1 = $this->sql['default']->prepare("INSERT INTO punchlist(ItemNUM , EmployeeNUM , StateNUM , Note , Time , Disabled) VALUES $insert_punchlist");
            $insert_query_1->execute();
            if ($old_item!=-1){
                $insert_query_2 = $this->sql['default']->prepare("INSERT INTO update_record(Time , ModifiedByNUM , ModifiedItemNUM , NewItemNUM , Note ) VALUES $insert_update");
                $insert_query_2->execute();
                $insert_query_3 = $this->sql['default']->prepare("UPDATE punchlist SET Disabled = 1 WHERE ItemNUM='$old_item'");
                $insert_query_3->execute();
            }
        }
        return $response;
    }
}
