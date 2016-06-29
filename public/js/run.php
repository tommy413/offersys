<html>
<head>
	<title>Cal</title>
</head>
<body>
	<div>
	<?php
		$dsn = "mysql:host=localhost;dbname=tommy";
		$db = new PDO($dsn, 'tommy','');
		$CountInteval=100;

		//loading data 
		$countquery = $db -> query("SELECT MAX(TeamNUM) FROM team");
		if(!$countquery)$teamcount=0;
		else{
			$countarr=$countquery->fetch();
			$teamcount=$countarr[0];
		}
		
		$buyorderquery = $db -> query("SELECT * FROM buyorder");
		if(!$buyorderquery)$buyorderarr=[];
		else{
			$buyorderarr = $buyorderquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$produceorderquery = $db -> query("SELECT * FROM produceorder");
		if(!$produceorderquery)$produceorderarr=[];
		else{
			$produceorderarr = $produceorderquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$sellorderquery = $db -> query("SELECT * FROM sellorder");
		if(!$sellorderquery)$sellorderarr=[];
		else{
			$sellorderarr = $sellorderquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$buypricequery = $db -> query("SELECT * FROM buyprice");
		if(!$buypricequery)$buypricearr=[];
		else{
			$buypricearr = $buypricequery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$sellpricequery = $db -> query("SELECT * FROM sellprice");
		if(!$sellpricequery)$sellpricearr=[];
		else{
			$sellpricearr = $sellpricequery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		$producingquery = $db -> query("SELECT * FROM producing");
		if(!$producingquery)$producingarr=[];
		else{
			$producingarr = $producingquery -> fetchAll(\PDO::FETCH_ASSOC);
		}

		//Collect data
		for ($i=1; $i <= 8 ; $i++) { 
			$sellamount[$i]=0;
			$buyamount[$i]=0;
			for ($j=1; $j <=$teamcount ; $j++) { 
				$sellamount[$i]=$sellamount[$i]+$sellorderarr[$j-1]['Goods$i'];
				$buyamount[$i]=$buyamount[$i]+$buyorderarr[$j-1]['Goods$i'];
			}
		}


		for ($i=1 ; $i<=$teamcount ; $i++){

			$teamquery = $db -> query("SELECT * FROM team WHERE TeamNUM='$i'");
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

			

			$buyorderquery = $db -> query("SELECT * FROM buyorder WHERE TeamNUM='$i'");
			if(!$buyorderquery)$buyorderarr=[];
			else{
				$buyorderarr = $buyorderquery -> fetchAll(\PDO::FETCH_ASSOC);
			}
		}
		$db = NULL; 
	?>
	</div>
	// <script type="text/javascript">
	// 	window.close();
	// </script>
</body>
</html>







