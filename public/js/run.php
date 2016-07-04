<html>
<head>
	<title>Cal</title>
</head>
<body>
	<div>
	<?php
		set_time_limit(900);
		$dsn = "mysql:host=localhost;dbname=tommy";
		$db = new PDO($dsn, 'tommy','');
		$BuyInteval=12500;
		$SellInteval=100;
		$begin=time();

		//loading data 
		$countquery = $db -> query("SELECT MAX(TeamNUM) FROM team");
		if(!$countquery)$teamcount=0;
		else{
			$countarr=$countquery->fetch();
			$teamcount=$countarr[0];
		}

		$teamquery = $db -> query("SELECT * FROM team Order BY TeamNUM ASC");
		if(!$teamquery)$teamarr=[];
		else{
			$teamarr = $teamquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$teambuyquery = $db -> query("SELECT * FROM teambuy Order BY TeamNUM ASC");
		if(!$teambuyquery)$teambuyarr=[];
		else{
			$teambuyarr = $teambuyquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$teamsellquery = $db -> query("SELECT * FROM teamsell Order BY TeamNUM ASC");
		if(!$teamsellquery)$teamsellarr=[];
		else{
			$teamsellarr = $teamsellquery -> fetchAll(\PDO::FETCH_ASSOC);
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

		$buypricequery = $db -> query("SELECT * FROM buyprice Order BY ProductNUM ASC");
		if(!$buypricequery)$buypricearr=[];
		else{
			$buypricearr = $buypricequery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$sellpricequery = $db -> query("SELECT * FROM sellprice Order BY GoodsNUM ASC");
		if(!$sellpricequery)$sellpricearr=[];
		else{
			$sellpricearr = $sellpricequery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$producingquery = $db -> query("SELECT * FROM producing Order BY GoodsNUM ASC");
		if(!$producingquery)$producingarr=[];
		else{
			$producingarr = $producingquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		for ($i=1; $i <= $teamcount ; $i++) { 
			for ($j=1; $j <= 8 ; $j++) { 
				$buyamount[$i][$j]=0;
				$sellamount[$i][$j]=0;
			}
		}
		
		$time1=time()-$begin;
		print ($time1);
		echo ("<br>");

		//correct data
		for ($i=1; $i <= $teamcount ; $i++) { 
			
			$money=$teamarr[$i]['MoneyCount'];
			for ($j=1; $j <=8 ; $j++) { 
				
				$sellorderarr[$i]["Goods$j"]=min($teamsellarr[$i]["Goods$j"],max($sellorderarr[$i]["Goods$j"],0));
				
				$produceorderarr[$i]["Producing$j"]=max($produceorderarr[$i]["Producing$j"],0);
				for ($k = 1; $k <= 8 ; $k++) {
					$index="Product".$k."Needed";
					if ($producingarr[$j-1][$index] > 0){ 
						$produceorderarr[$i]["Producing$j"]=min(floor(($teambuyarr[$i]["Product$k"]+$buyamount[$i][$k])/$producingarr[$j-1][$index]),min($produceorderarr[$i]["Producing$j"],$teamarr[$i]['Productivity']));
						
					}
				}
				for ($k = 1; $k <= 8 ; $k++) {
					$index="Product".$k."Needed";
					if ($producingarr[$j-1][$index] > 0){ 
						$buyamount[$i][$k]=$buyamount[$i][$k]-$producingarr[$j-1][$index]*$produceorderarr[$i]["Producing$j"];
					}
				}
					
				$teamarr[$i]['Productivity']=$teamarr[$i]['Productivity']-$produceorderarr[$i]["Producing$j"];

				$buyorderarr[$i]["Product$j"]=max($buyorderarr[$i]["Product$j"],0);
				$buyorderarr[$i]["Product$j"]=min( $buyorderarr[$i]["Product$j"],$money);
				$money=$money-$buyorderarr[$i]["Product$j"];
			}
			
		}
		


		//Collect data
		for ($i=1; $i <= 8 ; $i++) { 
			$sellcount[$i]=0;
			$buycount[$i]=0;
			for ($j=1; $j <=$teamcount ; $j++) { 
				$sellcount[$i]=$sellcount[$i]+$sellorderarr[$j]["Goods$i"];
				$buycount[$i]=$buycount[$i]+$buyorderarr[$j]["Product$i"];
			}
			$s=floor($sellcount[$i]/$SellInteval)+1;
			$b=floor($buycount[$i]/$BuyInteval)+1;
			if ($s>5)$s=5;
			if ($b>5)$b=5;
			$sellprice[$i]=$sellpricearr[$i-1]["Price$s"];
			$buyprice[$i]=$buypricearr[$i-1]["Price$b"];
			$pricequery = $db -> prepare("UPDATE buyprice SET Last=$b WHERE ProductNUM='$i' ");
			$pricequery -> execute();
			$pricequery = $db -> prepare("UPDATE sellprice SET Last=$s WHERE GoodsNUM='$i' ");
			$pricequery -> execute();


			echo "sell: ";
				print_r($sellcount[$i]);
				
				echo "intival:";
				print_r($$SellInteval);
				echo "<br>";
			echo "<br>";
		}

		
		$updatequery = $db -> prepare("DELETE FROM buyorder WHERE TeamNUM <> 0");
		$updatequery->execute();
		$updatequery = $db -> prepare("DELETE FROM produceorder WHERE TeamNUM <> 0");
		$updatequery->execute();
		$updatequery = $db -> prepare("DELETE FROM sellorder WHERE TeamNUM <> 0");
		$updatequery->execute();
		for ($i=1; $i <= $teamcount ; $i++) { 
			
				$updatequery = $db -> prepare("INSERT INTO buyorder(TeamNUM) VALUES ('$i')  ");
				$updatequery->execute();
				$updatequery = $db -> prepare("INSERT INTO produceorder(TeamNUM) VALUES ('$i') ");
				$updatequery->execute();
				$updatequery = $db -> prepare("INSERT INTO sellorder(TeamNUM) VALUES ('$i')");
				$updatequery->execute();
			
		}
		
		$time1=time()-$begin;
		print ($time1);
		echo ("<br>");

		for ($i=1 ; $i<=$teamcount ; $i++){
			
			//sell
			$benefit=0;
			for ($j=1; $j <= 8 ; $j++) { 
				$sellamount[$i][$j]=$sellamount[$i][$j]-$sellorderarr[$i]["Goods$j"];
				$benefit=$benefit+$sellprice[$j]*$sellorderarr[$i]["Goods$j"]*(1+0.01*$teamarr[$i]['SellBUFF']);
			

			//produce
			
				$sellamount[$i][$j]=$sellamount[$i][$j]+$produceorderarr[$i]["Producing$j"];
			
			
			
			//buy
			 
				$buyamount[$i][$j]=$buyamount[$i][$j]+floor($buyorderarr[$i]["Product$j"]/($buyprice[$j]*(1-0.01*$teamarr[$i]['BuyBUFF'])));
				$benefit=$benefit-floor($buyorderarr[$i]["Product$j"]/($buyprice[$j]*(1-0.01*$teamarr[$i]['BuyBUFF'])))*($buyprice[$j]*(1-0.01*$teamarr[$i]['BuyBUFF']));
			
				
			}
			
		
			$money=$teamarr[$i]['MoneyCount']+$benefit;
			$updatequery = $db -> prepare("UPDATE team SET MoneyCount = $money WHERE TeamNUM = '$i' ");
			$updatequery->execute();
			for ($j=1; $j <=8 ; $j++) { 
				$bought[$j]=$buyamount[$i][$j]+$teambuyarr[$i]["Product$j"];
				$produced[$j]=$sellamount[$i][$j]+$teamsellarr[$i]["Goods$j"];
			}
				$updatequery = $db -> prepare("UPDATE teambuy SET Product1 = $bought[1], Product2 = $bought[2],Product3 = $bought[3],Product4 = $bought[4],Product5 = $bought[5],Product6 = $bought[6],Product7 = $bought[7], Product8 = $bought[8] WHERE TeamNUM = '$i' ");
				$updatequery->execute();
				$updatequery = $db -> prepare("UPDATE teamsell SET Goods1 = $produced[1], Goods2 = $produced[2],Goods3 = $produced[3],Goods4 = $produced[4],Goods5 = $produced[5],Goods6 = $produced[6],Goods7 = $produced[7], Goods8 = $produced[8] WHERE TeamNUM = '$i' ");
				$updatequery->execute();
				
				
			
			
			echo "<br>";
		}
		


		$db = NULL; 

		$time1=time()-$begin;
		print ($time1);
		echo ("<br>");
	?>
	</div>
	   <script type="text/javascript">
	   	window.close();
	   </script> 
</body>
</html>







