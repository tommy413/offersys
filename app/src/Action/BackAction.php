<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class BackAction extends BaseAction
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

        $params = [
            'NowTime' => $nowtime,
            'RTime' => $rtime,
            'NowState' => $state
        ];

        $this->logger->debug("back_info", $params);
        $this->view->render($response, 'back.twig', $params);
        return $response;
    }

    // public function run(Request $request, Response $response, $args)
    // {
        
    // }
}
