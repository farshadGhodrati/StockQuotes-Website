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

$defaultSymbol = @$_REQUEST['StockSymbol_1'];


if($defaultSymbol == null)
{
	$defaultSymbol = @$_SESSION['sym'];
}

if(isset($_POST['symbolInput_2'])){
	$defaultSymbol = @$_POST['symbolInput_2'];
}
if($defaultSymbol == null)
{
	$defaultSymbol = "GE"; // default to GE symbol if nothing was typed
}

$_SESSION['sym'] = $defaultSymbol;


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


	
	<form id="quoteForm" action="quotes.php" method="post">
    <div id="search" class="centered">
		<input id="symbolInput" type="text" name="symbolInput_2" placeholder="Enter Stock Symbol" value=<?php print "$defaultSymbol"; ?> >
		<input id="searchButton" name ="searchButton1" type ="submit" value = "Search"><br>                                 
		
	</div>
	
	
	<?php //check for form data
	
	if(!empty($defaultSymbol))
	{
		$db = $objDBUtil->Open();
		
		$query = "SELECT symSymbol, symName FROM symbols " .
				 "WHERE symSymbol=" . $objDBUtil->DBQuotes($defaultSymbol);
				 
		$result = $db->query($query);
		if(! $result)
		{
			
		}	
		else
		{
			$row = @$result->fetch_assoc();
			$printSymbol = $row['symSymbol'];
			$printSymbolName = $row['symName'];
			$defaultSymbol = $printSymbol;
			
			$_SESSION['companyName'] = $printSymbolName;// keeping track of company name for search page

		}	
		@$result->free();
		
		$query = "SELECT qSymbol, qCashDividendAmount, qEarningsPerShare, qLastSalePrice, qBidPrice, qAskPrice, qTodaysLow, qTodaysHigh, qShareVolumeQty,
				 qNetChangePrice, q52WeekLow, q52WeekHigh, qTotalOutstandingsharesQty, qCurrentYieldPct, qCurrentPERatio,
				 qPreviousClosePrice, qQuoteDateTime, qNetChangePct FROM quotes " .
				 "WHERE qSymbol=" . $objDBUtil->DBQuotes($defaultSymbol).
				 "order by qQuoteDateTime desc";
				 
		$result = $db->query($query);
		if(! $result)
		{
			
		}	
		else
		{
			$row = @$result->fetch_assoc();
			$LastPrice = $row['qLastSalePrice'];
			if(empty($LastPrice)){ 
				$LastPrice = "N/A"; 
			}
			else {
				$LastPrice = number_format("$LastPrice", 2);
			}
			
			$TodayLow = $row['qTodaysLow'];
			if(empty($TodayLow)){
				$TodayLow = "N/A";
			}
			else{
				$TodayLow = number_format("$TodayLow", 2);
			}
			
			$TodayHigh = $row['qTodaysHigh'];
			if(empty($TodayHigh)){
				$TodayHigh = "N/A";
			}
			else{
				$TodayHigh = number_format("$TodayHigh", 2);
			}
			
			$ChangePrice = $row['qNetChangePrice'];
			if(empty($ChangePrice)){
				$ChangePrice = "N/A";
			}
			else{
				$ChangePrice = number_format("$ChangePrice", 2);
			}
			
			$ChangePersent = $row['qNetChangePct'];
			if(empty($ChangePersent)){
				$ChangePersent = "N/A";
			}
			else{
				$ChangePersent = number_format("$ChangePersent", 2);
			}
			
			$ShareVolume = $row['qShareVolumeQty'];
			if(empty($ShareVolume)){
				$ShareVolume = "N/A";
			}
			else{
				$ShareVolume = number_format("$ShareVolume");
			}
			
			$BidPrice = $row['qBidPrice'];
			if(empty($BidPrice)){
				$BidPrice = "N/A";
			}
			else{
				$BidPrice = number_format("$BidPrice", 2);
			}
			
			$AskPrice = $row['qAskPrice'];
			if(empty($AskPrice)){
				$AskPrice = "N/A";
			}
			else{
				$AskPrice = number_format("$AskPrice", 2);
			}
			
			$sym = $row['qSymbol'];
			$time = $row['qQuoteDateTime'];
			$EarningPerShare = $row['qEarningsPerShare'];
			if(empty($EarningPerShare)){
				$EarningPerShare = "N/A";
			}
			else{
				$EarningPerShare = number_format("$EarningPerShare", 2);
			}
			
			$CashDividentAmount = $row['qCashDividendAmount'];
			if(empty($CashDividentAmount)){
				$CashDividentAmount = "N/A";
			}
			else{
				$CashDividentAmount = number_format("$CashDividentAmount", 2);
			}
			
			$PreviousClosePrice = $row['qPreviousClosePrice'];
			if(empty($PreviousClosePrice)){
				$PreviousClosePrice = "N/A";
			}
			else{
				$PreviousClosePrice = number_format("$PreviousClosePrice", 2);
			}
			
			$weeksHigh = $row['q52WeekHigh'];
			if(empty($weeksHigh)){
				$weeksHigh = "N/A";
			}
			else{
				$weeksHigh = number_format("$weeksHigh", 2);
			}
			
			$weeksLow = $row['q52WeekLow'];
			if(empty($weeksLow)){
				$weeksLow = "N/A";
			}
			else{
				$weeksLow = number_format("$weeksLow", 2);
			}
			
			$totalShares = $row['qTotalOutstandingsharesQty'];
			if(empty($totalShares)){
				$totalShares = "N/A";
			}
			else{
				$totalShares = number_format("$totalShares");
			}
			
			$DivYield = $row['qCurrentYieldPct'];
			if(empty($DivYield)){
				$DivYield = "N/A";
			}
			else{
				$DivYield = number_format("$DivYield", 2);
			}
			
			$PEratio = $row['qCurrentPERatio'];
			if(empty($PEratio)){
				$PEratio = "N/A";
			}
			else{
				$PEratio = number_format("$PEratio", 2);
			}
			
		}	
		@$result->free();
		$objDBUtil->Close(); // Close connection 

	}
	
	if($ChangePrice < 0)// choosing color depending on price change
	{
		$color = 'red';
	}
	else
	{
		$color = 'green';
	}
	
	?>
	
	
	<div id = "companySymbol">
		<?php print "$printSymbolName"; ?>    (<?php print "$sym"; ?>)
	</div>
	<br>
	<div id = "exchangeSymbol">
		<h3><?php print"$time"; ?></h3>
	</div>
 



    <table style="width:580; margin: 0 auto;" style="height : 75%" id= "quotesTable" >

	 <tr>
        
          <td width=100>Last</td>
          <td width=100 align=right><?php print "$LastPrice"; ?></td>
          <td width=110 >&nbsp;</td>
          <td width=100>Prev Close</td>
          <td width=80 align=right><?php print "$PreviousClosePrice"; ?></td>
        </tr>

        <tr>
      
          <td>Change</td>
          <td align=right><font color=<?php print "$color"; ?>><?php print "$ChangePrice"; ?></font></td>
          <td>&nbsp;</td>
          <td>Bid</td>
          <td align=right><?php print "$BidPrice"; ?></td>
        </tr>

        <tr>
         
          <td>%Change</td>
          <td align=right><font color=<?php print "$color"; ?>><?php print "$ChangePersent %"; ?></font></td>
          <td>&nbsp;</td>
          <td>Ask</td>
          <td align=right><?php print "$AskPrice"; ?></td>
        </tr>

        <tr>
     
          <td>High</td>
          <td align=right><?php print "$TodayHigh"; ?></td>
          <td>&nbsp;</td>
          <td>52 Week High</td>
          <td align=right><?php print "$weeksHigh"; ?></td>
        </tr>

        <tr>
     
          <td>Low</td>
          <td align=right><?php print "$TodayLow"; ?></td>
          <td>&nbsp;</td>
          <td>52 Week Low</td>
          <td align=right><?php print "$weeksLow"; ?></td>
          </tr>

        <tr>
       
          <td>Daily Volume</td>
          <td align=right><?php print "$ShareVolume"; ?></td>
          <td>&nbsp;</td>
          <td>PE Ratio</td>
          <td align=right><?php print "$PEratio"; ?></td>
        </tr>
		
        <tr>
       
          <td>Earning/Share</td>
          <td align=right><?php print "$EarningPerShare"; ?></td>
          <td>&nbsp;</td>
          <td>Total Shares</td>
          <td align=right><?php print "$totalShares"; ?></td>
         </tr>
		  
		<tr>
   
          <td>Div/Share</td>
          <td align=right><?php print "$CashDividentAmount"; ?></td>
          <td>&nbsp;</td>
          <td>Div. Yield</td>
          <td align=right><?php print "$DivYield"; ?></td>
        </tr>
	
	
	
    </table>
	</form>
	<form id="quoteForm" action="history.php" method="post">
	 <div id="search" class="centered">
		<input id="searchButton2" name="historyButton" type ="submit" value = "Look Up (<?php print "$defaultSymbol"; ?>) History">
		<input type="hidden" name="GetSymbol" value="<?php "$defaultSymbol"; ?>">
		
	</div>
	</form>
	<div class="wrapper">
    <div id="terms">
        <center>Copyright 2016 | Farshad Ghodrati</center>
    </div>
</html>
<?php // This is the LAST section in a PHP webpage file
ob_end_flush();
?>