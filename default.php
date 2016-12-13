<?php // This must be the FIRST line in a PHP webpage file
ob_start(); // Enable output buffering
// name1.php
//
// Specify no-caching header controls for page
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0


if(isset($_POST['go'])){
	$defaultSymbol = @$_REQUEST['StockSymbol'];
}
else{
	$defaultSymbol = "";
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
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<title>Stock Buzz</title>
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
  
	
	<form name="searchForm" action="quotes.php" method="get">
		<div id="search" class="centered">
			<input id="symbolInput" type="text" name="StockSymbol_1" placeholder="Enter Stock Symbol" value="<?php print "$defaultSymbol"; ?>" />
			<input id="SearchButton"  type="submit" value="Get Quote" name="go">
		</div>
	</form>
	<h2>Keep calm and look up<br>some stocks </h2>
	
    <div id="terms">
        <center>Copyright 2016 | Farshad Ghodrati</center>
    </div>
</html>
<?php // This is the LAST section in a PHP webpage file
ob_end_flush();
?> 










