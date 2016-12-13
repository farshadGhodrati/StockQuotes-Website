<?php // This must be the FIRST line in a PHP webpage file
ob_start(); // Enable output buffering
// name1.php
//
// Specify no-caching header controls for page
require "DbUtil.inc";
$objDBUtil = new DbUtil;

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
session_start();
$defaultSymbol_1 = "";


$defaultSymbol_1 = $_SESSION['sym'];


if(isset($_POST['symbolInput_3']))
{
		$defaultSymbol_1 = @$_POST['symbolInput_3'];
}
if($defaultSymbol_1 == null)
{
	$defaultSymbol_1 = "GE"; // default to GE symbol if nothing was typed
}

?> 
<html>
	<head>
		<link rel = "stylesheet" type ="text/css"  href="style.css">
			    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<title>Stock Buzz </title>
	</head>
	
<body>
    <div id="header">
		<h1 id="title">Stock Buzz</h1>
    </div>

    <div class="nav">
		<ul>
			<li class="Home"><a href="default.php">Home</a></li>
			<li class="Search"><a href="search.php">Search</a></li>
			<li class="Quotes"><a href="quotes.php">Quotes</a></li>
			<li class="History"><a href="history.php">History</a></li>
		</ul>
	</div>
</body>




	<form id="historyForm" method="post">
	<div id="search" class="centered">
		<input id="symbolInput" type="text" name="symbolInput_3" placeholder="Enter Stock Symbol" value=<?php print"$defaultSymbol_1"; ?> >
		<input id="searchButton" name ="searchButton" type ="submit" value = "Search"><br>
	</div> 
	</form>	
	
	<?php
	
	if(!empty($defaultSymbol_1))
	{
		$db = $objDBUtil->Open();
		
		$query = "SELECT symSymbol, symName FROM symbols WHERE symSymbol=". $objDBUtil->DBQuotes($defaultSymbol_1);
				 
		$result = $db->query($query);
		
		if(!$result)
		{
			
		}
		else
		{
			$row = @$result->fetch_assoc();
			$printSymbol = $row['symSymbol'];
			$printSymbolName = $row['symName'];
			$defaultSymbol_1 = $printSymbol;
			$_SESSION['companyName'] = $printSymbolName; // keeping track of company name for search page
			
		}
		@$result->free();
		
		$query = "SELECT qSymbol, qCashDividendAmount, qEarningsPerShare, qLastSalePrice, qBidPrice, qAskPrice, qTodaysLow, qTodaysHigh, qShareVolumeQty,
				 qNetChangePrice, q52WeekLow, q52WeekHigh, qTotalOutstandingsharesQty, qCurrentYieldPct, qCurrentPERatio,
				 qPreviousClosePrice, qQuoteDateTime, qNetChangePct FROM quotes " .
				 "WHERE qSymbol=" . $objDBUtil->DBQuotes($defaultSymbol_1).
				 "order by qQuoteDateTime desc";
				 

	}
	
	
	?>

	<div id = "companySymbol">
		<?php print "$printSymbolName"; ?>   (<?php print "$printSymbol"; ?>)
	</div>
	<br>

	<div id="history" class= "scroll">

		<table  style= 'width:100%' style ='height:60%' id= 'historyTable'>
			
		<tr>
          <td width=65  align=center ><b>Date</b></td>
          <td width=75  align=center  ><b>Last</b></td>
          <td width=75  align=center  ><b>Change</b></td>
          <td width=75  align=center  ><b>% Chg</b></td>
          <td width=80  align=center  ><b>Volume</b></td>
        </tr>

	<?php 
		$result = @$db->query($query);
		while($row = @$result->fetch_assoc()) 
		{
			print "<tr>";
				$date = $row['qQuoteDateTime'];
				$date = date("M d, Y", strtotime($date)); 
				print "<td>{$date}</td>"; 
			
				$lastPrice = $row['qLastSalePrice'];
				if(empty($lastPrice)){
				$lastPrice = "N/A";
				}
				else{
					$lastPrice = number_format("$lastPrice", 2);
				}
				print "<td>$lastPrice</td>";
			
				$priceChange = $row['qNetChangePrice'];
				if(empty($priceChange)){
				$priceChange = "N/A";
				}
				else{
					$priceChange = number_format("$priceChange",2);
				}
				if($priceChange < 0)
				{
					print "<td><font color='red'>$priceChange</font></td>";
				}
				else
				{
					print "<td><font color='green'>$priceChange</font></td>";
				}
			
				$priceChangePct = $row['qNetChangePct'];
				if(empty($priceChangePct)){
				$priceChangePct = "N/A";
				}
				else{
					$priceChangePct = number_format("$priceChangePct",2);
				}
				if($priceChangePct < 0)
				{
					print "<td><font color='red'>$priceChangePct</font></td>";
				}
				else
				{
					print "<td><font color='green'>$priceChangePct</font></td>";
				}
				
				$volume = $row['qShareVolumeQty'];
				if(empty($volume)){
				$volume = "N/A";
				}
				else{
					$volume = number_format("$volume");
				}
				print "<td>$volume</td>";
			print "</tr>\n";
		}
		@$result->free();
		$objDBUtil->Close(); // Close connection 
	?>


		</table>           
	</div>
	
	<form id="historyForm" action="quotes.php" method="post">
	<div id="search" class="centered">
		<input id="searchButton2" name ="quoteButton" type ="submit" value = "Look Up (<?php print "$defaultSymbol_1"; ?>) Quote">
	</div> 
	</form>	
	
	<?php $_SESSION['sym'] = $defaultSymbol_1; ?>
	
	<div class="wrapper">
    <div id="terms">
        <center>Copyright 2016 | Farshad Ghodrati</center>
    </div>
</html>
<?php // This is the LAST section in a PHP webpage file
ob_end_flush();
?>