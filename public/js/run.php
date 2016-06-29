<html>
<head>
	<title>Cal</title>
</head>
<body>
	<div>
	<?php
		$dsn = "mysql:host=localhost;dbname=tommy";
		$db = new PDO($dsn, 'tommy','');
		$CountInteval=50;

		//loading data 
		$countquery = $db -> query("SELECT MAX(TeamNUM) FROM team");
		if(!$countquery)$teamcount=0;
		else{
			$countarr=$countquery->fetch();
			$teamcount=$countarr[0];
		}
		
		$buyorderquery = $db -> query("SELECT * FROM buyorder Order BY TeamNUM ASC");
		if(!$buyorderquery)$buyorderarr=[];
		else{
			$buyorderarr = $buyorderquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$produceorderquery = $db -> query("SELECT * FROM produceorder Order BY TeamNUM ASC");
		if(!$produceorderquery)$produceorderarr=[];
		else{
			$produceorderarr = $produceorderquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$sellorderquery = $db -> query("SELECT * FROM sellorder Order BY TeamNUM ASC");
		if(!$sellorderquery)$sellorderarr=[];
		else{
			$sellorderarr = $sellorderquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$buypricequery = $db -> query("SELECT * FROM buyprice Order BY TeamNUM ASC");
		if(!$buypricequery)$buypricearr=[];
		else{
			$buypricearr = $buypricequery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$sellpricequery = $db -> query("SELECT * FROM sellprice Order BY TeamNUM ASC");
		if(!$sellpricequery)$sellpricearr=[];
		else{
			$sellpricearr = $sellpricequery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$producingquery = $db -> query("SELECT * FROM producing Order BY TeamNUM ASC");
		if(!$producingquery)$producingarr=[];
		else{
			$producingarr = $producingquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		//Collect data
		for ($i=1; $i <= 8 ; $i++) { 
			$sellamount[$i]=0;
			$buyamount[$i]=0;
			for ($j=1; $j <=$teamcount ; $j++) { 
				$sellamount[$i]=$sellamount[$i]+min($sellorderarr[$j-1]["Goods$i"],$teamsell[$j-1]["Goods$i"]);
				$buyamount[$i]=$buyamount[$i]+$buyorderarr[$j-1]["Product$i"];
			}
			$sellitem=floor($sellamount[$i]/$CountInteval);
			$buyitem=floor($buyamount[$i]/$CountInteval);
			$sellprice[$i]=$sellpricearr[$i-1]["Price$sellitem"];
			$buyprice[$i]=$buypricearr[$i-1]["Price$buyitem"];
		}

		//
		for ($i=1 ; $i<=$teamcount ; $i++){

			$teamquery = $db -> query("SELECT * FROM team WHERE TeamNUM='$i' ");
			if(!$teamquery)$teamarr=[];
			else{
				$teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
			}

			$teambuyquery = $db -> query("SELECT * FROM teambuy WHERE TeamNUM='$i'");
			if(!$teambuyquery)$teambuyarr=[];
			else{
				$teambuyarr = $teambuyquery -> fetchAll(\PDO::FETCH_ASSOC);
			}

			$teamsellquery = $db -> query("SELECT * FROM teamsell WHERE TeamNUM='$i'");
			if(!$teamsellquery)$teamsellarr=[];
			else{
				$teamsellarr = $teamsellquery -> fetchAll(\PDO::FETCH_ASSOC);
			}

			$money=$teamarr[0]['MoneyCount'];
			$benefit=0;
			$produceamount=$teamarr[0]['Productivity'];
			//sell
			for ($j=1; $j <= 8 ; $j++) { 
				$benefit=$benefit+$sellprice[$j]*min($sellorderarr[$i-1]["Goods$j"],$teamsell[0]["Goods$j"])*(1+0.01*$teamarr[0]['SellBUFF']);
				$aftersell=$teamsell[0]["Goods$j"]-min($sellorderarr[$i-1]["Goods$j"],$teamsell[0]["Goods$j"]);
				$updatequery = $db -> prepare("UPDATE teamsell SET Goods$j = $aftersell WHERE TeamNUM = '$i' ");
				$updatequery->execute();
				$teamsell[0]["Goods$j"]=$aftersell;
			}

			//produce
			for ($j=1; $j <= 8; $j++) { 
				for ($k = 1; $k <= 8  ; $k++) {
					if ($producingarr[$j-1]["Product$k"] > 0){ 
						$produceamount=min( $produceorder[$i-1]["Goods$j"],
											floor($teambuyarr[0]["Product$k"]/$producingarr[$j-1]["Product$k"]),
											$produceamount,
											$teamarr[0]['Productivity']);
					}
				}
				for ($k = 1; $k <= 8  ; $k++) {
					if ($producingarr[$j-1]["Product$k"] > 0){ 
						$afterproduce = $teambuyarr[0]["Product$k"]-$producingarr[$j-1]["Product$k"]*$produceamount;
						$updatequery = $db -> prepare("UPDATE teambuy SET Product$k = $afterproduce WHERE TeamNUM = '$i' ");
						$updatequery->execute();
						$teambuyarr[0]["Product$k"]=$afterproduce;
					}
				}
				$produced=$teamsell[0]["Goods$j"]+$produceamount;
				$updatequery = $db -> prepare("UPDATE teamsell SET Goods$j = $produced WHERE TeamNUM = '$i' ");
				$updatequery->execute();
			}

			//buy
			if ($money>0){
				for ($j=1; $j <=8 ; $j++) { 
					$money=$money-$buyorderarr[$i-1]["Product$j"]*$buyprice[$j]*(1-0.01*$teamarr[0]['BuyBUFF']);
					$afterbuy=$teambuyarr[0]["Product$j"]+$buyorderarr[$i-1]["Product$j"];
					$updatequery = $db -> prepare("UPDATE teambuy SET Product$j = $afterbuy WHERE TeamNUM = '$i' ");
					$updatequery->execute();
				}
			}

			$money=$money+$benefit;
			$updatequery = $db -> prepare("UPDATE team SET MoneyCount = $money WHERE TeamNUM = '$i' ");
			$updatequery->execute();

		}
		for ($i=1; $i <= $teamcount ; $i++) { 
			for ($j=1; $j<=8  ; $j++) { 
				$updatequery = $db -> prepare("UPDATE buyorder SET Product$j = 0 WHERE TeamNUM = '$i' ");
				$updatequery->execute();
				$updatequery = $db -> prepare("UPDATE preduceorder SET Goods$j = 0 WHERE TeamNUM = '$i' ");
				$updatequery->execute();
				$updatequery = $db -> prepare("UPDATE sellorder SET Goods$j = 0 WHERE TeamNUM = '$i' ");
				$updatequery->execute();
			}
			
		}
		$db = NULL; 
	?>
	</div>
	 <script type="text/javascript">
	 	window.close();
	 </script>
</body>
</html>







