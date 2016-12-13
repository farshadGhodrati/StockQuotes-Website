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
$symbol = @$_SESSION['sym3'];
$fullName = @$_SESSION['companyName'];


$quoteSym = @$_GET['symbolname'];//gets sym from url
if(!empty($quoteSym))
{
	$_SESSION['sym'] = $quoteSym;
}
else{
	$quoteSym = @$_SESSION['sym'];
}

if(isset($_POST['symbolInput']))
{
	$symbol = @$_POST['symbolInput'];
	$_SESSION['sym3'] = $symbol;
	
}
if(empty($symbol))
{
	$symbol = $fullName;
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



	<form id="searchForm" action="search.php" method="post">
	<div id="search" class="centered">
		<input id="symbolInput" type="text" name="symbolInput" placeholder="Enter Company Name" value=<?php print"$symbol"; ?> >
		<input id="searchButton" name ="searchButton" type ="submit" value = "Search">
	</div>  
	</form>
	
	<?php
	if(!empty($symbol))
	{
		$db = $objDBUtil->Open();
		
		$query = "SELECT symName, symSymbol FROM symbols WHERE symSymbol or symName LIKE '%$symbol%' order by symSymbol asc";
		
	}
	
	
	?>

	<div id = "companySymbol">Search Table </div><br>
	<div id="searchpage" class= "scroll">

		<table style= 'width:100%' style ='height:60%' id= 'searchTable'>
			
<tr><th>Company Name</th><th>Symbol</th><th>Select</th>

<?php 
	$result = @$db->query($query);
	while($row = @$result->fetch_assoc()) 
	{
	print "<tr>";
	$symbolName = $row['symName'];
	print "<td align='centered'>$symbolName</td>"; 
	
	$symbol_1 = $row['symSymbol'];
	print "<td align='centered'>$symbol_1</td>"; 
	print "<td><a href='search.php?symbolname=$symbol_1'>select</a></td>"; 
	 
	print "</tr>\n";
	}
	@$result->free();
	$objDBUtil->Close(); // Close connection 

?>


		</table>
	</div>
		<form id="quoteForm" action="history.php" method="post">
	 <div id="search" class="centered">
		<input id="searchButton3" name="historylookup" type ="submit" value = "Look Up (<?php print "$quoteSym"; ?>) History">
		<input type="hidden" name="GetSymbol" value="<?php "$defaultSymbol"; ?>">
		
	</div>
	</form>
		<form id="quoteForm" action="quotes.php" method="post">
	 <div id="search" class="centered">
		<input id="searchButton3" name="quotelookup" type ="submit" value = "Look Up (<?php print "$quoteSym"; ?>) Quotes">
		<input type="hidden" name="GetSymbol" value="<?php "$defaultSymbol"; ?>">
		
	</div>
	</form>
	
	<div class="wrapper">
    <div id="terms">
        <center>Copyright 2016 | Farshad Ghodrati</center>
    </div>
</div>
	
</html>
<?php // This is the LAST section in a PHP webpage file
ob_end_flush();
?>